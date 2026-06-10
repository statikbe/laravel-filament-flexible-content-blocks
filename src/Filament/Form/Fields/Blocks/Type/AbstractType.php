<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type;

use Closure;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use function Filament\Support\get_model_label;

/**
 * Selectable type for different models, where different types of models or data can be selected.
 * This class helps with implementing the necessary functionality for a Select component.
 * For instance for CTAs.
 *
 * In case, the type or model needs a different label in the Select component, you can overwrite
 * `getOptionLabelFromRecordUsing` with a custom callback.
 */
abstract class AbstractType
{
    protected ?string $label = null;

    public Closure $getOptionLabelUsing;

    public Closure $getSearchResultsUsing;

    public Closure $getOptionsUsing;

    protected ?Closure $modifyOptionsQueryUsing = null;

    protected ?array $searchColumns = ['title', 'intro', 'overview_title', 'overview_description'];

    protected ?string $titleColumnName = 'title';

    protected ?Closure $getOptionLabelFromRecordUsing = null;

    protected int $optionsLimit = 50;

    protected string $model;

    /**
     * Memoized list of configured search columns that actually exist on the
     * model's table, keyed by "connection.table".
     *
     * @var array<string, array<int, string>>
     */
    protected array $existingSearchColumns = [];

    protected function setUp(): void
    {
        $this->getSearchResultsUsing(function (Select $component, ?string $search): array {
            $query = $this->getModel()::query();

            if ($this->modifyOptionsQueryUsing) {
                $query = $component->evaluate($this->modifyOptionsQueryUsing, [
                    'query' => $query,
                ]) ?? $query;
            }

            $locale = $this->resolveLocale($component);

            $this->applyDefaultOrder($query, $locale);

            $model = $query->getModel();

            // Only search columns that actually exist on the model's table, so a
            // model that does not define every configured column (e.g. no
            // overview_title / overview_description) does not break the query.
            $searchColumns = $this->getExistingSearchColumns($query);

            // Without any searchable column there is nothing to match against.
            // Returning early avoids an empty where(...) group, which SQL treats
            // as "match everything" and would return every record.
            if (empty($searchColumns)) {
                return [];
            }

            // whereLike (caseSensitive: false) lets Laravel pick the correct
            // case-insensitive operator per driver (ilike on PostgreSQL, like on
            // MySQL). For translatable columns the reference is a JSON arrow path
            // for the active locale, so we match the human-readable string rather
            // than the raw JSON document.
            $query->where(function (Builder $query) use ($search, $searchColumns, $model, $locale): void {
                foreach ($searchColumns as $searchColumnName) {
                    $query->orWhereLike(
                        $this->resolveColumnReference($model, $searchColumnName, $locale),
                        "%{$search}%",
                    );
                }
            });

            $baseQuery = $query->getQuery();

            if (isset($baseQuery->limit)) {
                $component->optionsLimit($baseQuery->limit);
            } else {
                $query->limit($component->getOptionsLimit());
            }

            $keyName = $query->getModel()->getKeyName();

            if ($this->hasOptionLabelFromRecordUsingCallback()) {
                return $query
                    ->get()
                    ->mapWithKeys(fn (Model $record) => [
                        $record->{$keyName} => $this->getOptionLabelFromRecord($record, $locale),
                    ])
                    ->toArray();
            }

            return $query
                ->pluck($this->getTitleColumnName(), $keyName)
                ->toArray();
        });

        $this->getOptionsUsing(function (Select $component): ?array {
            if (($component->isSearchable()) && ! $component->isPreloaded()) {
                return null;
            }

            $query = $this->getModel()::query();

            if ($this->modifyOptionsQueryUsing) {
                $query = $component->evaluate($this->modifyOptionsQueryUsing, [
                    'query' => $query,
                ]) ?? $query;
            }

            $locale = $this->resolveLocale($component);

            $this->applyDefaultOrder($query, $locale);

            $keyName = $query->getModel()->getKeyName();

            if ($this->hasOptionLabelFromRecordUsingCallback()) {
                return $query
                    ->get()
                    ->mapWithKeys(fn (Model $record) => [
                        $record->{$keyName} => $this->getOptionLabelFromRecord($record, $locale),
                    ])
                    ->toArray();
            }

            return $query
                ->pluck($this->getTitleColumnName(), $keyName)
                ->toArray();
        });

        $this->getOptionLabelUsing(function (Select $component, $value) {
            $query = $this->getModel()::query();

            $query->where($query->getModel()->getKeyName(), $value);

            if ($this->modifyOptionsQueryUsing) {
                $query = $component->evaluate($this->modifyOptionsQueryUsing, [
                    'query' => $query,
                ]) ?? $query;
            }

            $record = $query->first();

            if (! $record) {
                return null;
            }

            if ($this->hasOptionLabelFromRecordUsingCallback()) {
                return $this->getOptionLabelFromRecord($record, $this->resolveLocale($component));
            }

            return $record->getAttributeValue($this->getTitleColumnName());
        });

        $this->getOptionLabelFromRecordUsing(function (Model $record, ?string $locale = null): string {
            // default implementation, please overwrite if your model differs:
            return method_exists($record, 'translate') ? $record->translate($this->getTitleColumnName(), $locale ?? app()->getLocale()) : $record->{$this->getTitleColumnName()};
        });
    }

    /**
     * Apply the default ordering on the title column when the query is not
     * already ordered. For translatable columns the active locale's value is
     * extracted via a JSON arrow path, which both keeps the ordering on the
     * human-readable string and avoids PostgreSQL's "could not identify an
     * ordering operator for type json" error.
     */
    protected function applyDefaultOrder(Builder $query, ?string $locale = null): void
    {
        if (! empty($query->getQuery()->orders)) {
            return;
        }

        /** @var Connection $connection */
        $connection = $query->getConnection();
        $columnReference = $this->resolveColumnReference($query->getModel(), $this->getTitleColumnName(), $locale);
        $column = $connection->getQueryGrammar()->wrap($columnReference);

        // Lowercase so the ordering is case-insensitive on every driver.
        $query->orderByRaw('lower('.$column.')');
    }

    /**
     * Resolve the active locale for the Select component, falling back to the
     * application locale when the Livewire component does not expose one.
     */
    protected function resolveLocale(Select $component): string
    {
        $livewire = $component->getLivewire();

        if (method_exists($livewire, 'getActiveSchemaLocale')) {
            return $livewire->getActiveSchemaLocale() ?? app()->getLocale();
        }

        return app()->getLocale();
    }

    /**
     * Resolve the column reference to use in a query. For translatable columns
     * this returns a JSON arrow path (e.g. "title->en") so the active locale's
     * value is compared instead of the raw JSON document; other columns are
     * returned unchanged.
     */
    protected function resolveColumnReference(Model $model, string $column, ?string $locale): string
    {
        if ($locale !== null
            && method_exists($model, 'isTranslatableAttribute')
            && $model->isTranslatableAttribute($column)) {
            return "{$column}->{$locale}";
        }

        return $column;
    }

    /**
     * Return the configured search columns that actually exist on the model's
     * table. The schema lookup is memoized per table so it is not repeated on
     * every search request.
     *
     * @return array<int, string>
     */
    protected function getExistingSearchColumns(Builder $query): array
    {
        /** @var Connection $connection */
        $connection = $query->getConnection();
        $table = $query->getModel()->getTable();

        // Key the cache by connection and table so a query swapped onto a
        // different connection (e.g. a tenant database) with the same table name
        // does not reuse another connection's column lookup.
        $cacheKey = $connection->getName().'.'.$table;

        if (! isset($this->existingSearchColumns[$cacheKey])) {
            $schemaBuilder = $connection->getSchemaBuilder();

            $this->existingSearchColumns[$cacheKey] = array_values(array_filter(
                $this->getSearchColumns() ?? [],
                fn (string $column): bool => $schemaBuilder->hasColumn($table, $column),
            ));
        }

        return $this->existingSearchColumns[$cacheKey];
    }

    public function model(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function label(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function titleColumnName(?string $name): static
    {
        $this->titleColumnName = $name;

        return $this;
    }

    public function searchColumns(?array $columns): static
    {
        $this->searchColumns = $columns;

        return $this;
    }

    public function modifyOptionsQueryUsing(?Closure $callback): static
    {
        $this->modifyOptionsQueryUsing = $callback;

        return $this;
    }

    public function getOptionsUsing(Closure $callback): static
    {
        $this->getOptionsUsing = $callback;

        return $this;
    }

    public function getSearchResultsUsing(Closure $callback): static
    {
        $this->getSearchResultsUsing = $callback;

        return $this;
    }

    public function getOptionLabelUsing(Closure $callback): static
    {
        $this->getOptionLabelUsing = $callback;

        return $this;
    }

    public function getOptionLabelFromRecordUsing(?Closure $callback): static
    {
        $this->getOptionLabelFromRecordUsing = $callback;

        return $this;
    }

    public function getOptionLabelFromRecord(Model $record, ?string $locale = null): string
    {
        return ($this->getOptionLabelFromRecordUsing)($record, $locale);
    }

    public function hasOptionLabelFromRecordUsingCallback(): bool
    {
        return $this->getOptionLabelFromRecordUsing !== null;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getLabel(): string
    {
        if ($this->label) {
            return $this->label;
        }

        if ($resource = Filament::getModelResource($this->getModel())) {
            /** @var class-string<resource> $resource */
            return Str::ucfirst($resource::getModelLabel());
        }

        return Str::ucfirst(get_model_label($this->getModel()));
    }

    public function getAlias(): string
    {
        // TODO check if this needs to be changed for projects without morph map.
        return app($this->getModel())->getMorphClass();
    }

    public function getSearchColumns(): ?array
    {
        return $this->searchColumns ?? [$this->getTitleColumnName()];
    }

    public function getTitleColumnName(): string
    {
        if (blank($this->titleColumnName)) {
            throw new Exception("Type [{$this->getModel()}] must have a [titleColumnName()] set.");
        }

        return $this->titleColumnName;
    }

    public function getOptionsLimit(): int
    {
        return $this->optionsLimit;
    }
}
