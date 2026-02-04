<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Closure;
use Filament\Forms\Components\Concerns\CanAllowHtml;
use Filament\Forms\Components\Concerns\CanBePreloaded;
use Filament\Forms\Components\Concerns\CanBeSearchable;
use Filament\Forms\Components\Concerns\HasLoadingMessage;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Concerns\HasLabel;
use Filament\Schemas\Components\Concerns\HasName;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type\OverviewType;

class OverviewItemField extends Component
{
    use CanAllowHtml;
    use CanBePreloaded;
    use CanBeSearchable;
    use HasLabel {
        getLabel as getLabelFromHasLabel;
    }
    use HasLoadingMessage;
    use HasName;

    protected string $view = 'filament-schemas::components.grid';

    public bool|Closure $isRequired = false;

    protected int|Closure $optionsLimit = 50;

    public array|Closure $types = [];

    final public function __construct(string $name)
    {
        $this->name($name);
        $this->isSearchable = true;
        $this->components(fn (): array => $this->getFieldComponents());
    }

    public static function make(string $name): static
    {
        $static = app(static::class, ['name' => $name]);
        $static->configure();

        return $static;
    }

    public function getFieldComponents(): array
    {
        $types = $this->getTypes();
        $isRequired = $this->isRequired();

        return [
            Grid::make(6)
                ->schema([
                    Select::make('overview_model')
                        ->columnSpan(2)
                        ->label($this->getLabel())
                        ->hiddenLabel()
                        ->selectablePlaceholder('Select an overview model')
                        ->options(array_map(
                            fn (OverviewType $type): string => $type->getLabel(),
                            $types,
                        ))
                        ->required($isRequired)
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('overview_id', null);
                        }),
                    Select::make('overview_id')
                        ->columnSpan(4)
                        ->label(function (Get $get) use ($types): ?string {
                            $selectedType = $types[$get('overview_model')] ?? null;

                            return $selectedType?->getLabel();
                        })
                        ->hiddenLabel()
                        ->options(function (Select $component, Get $get) use ($types): ?array {
                            $selectedType = $types[$get('overview_model')] ?? null;
                            if (! $selectedType) {
                                return null;
                            }

                            return ($selectedType->getOptionsUsing)($component);
                        })
                        ->getSearchResultsUsing(function (Select $component, Get $get, ?string $search) use ($types): array {
                            $selectedType = $types[$get('overview_model')] ?? null;
                            if (! $selectedType) {
                                return [];
                            }

                            return ($selectedType->getSearchResultsUsing)($component, $search);
                        })
                        ->getOptionLabelUsing(function (Select $component, Get $get, mixed $value) use ($types): mixed {
                            $selectedType = $types[$get('overview_model')] ?? null;
                            if (! $selectedType) {
                                return $value;
                            }

                            return ($selectedType->getOptionLabelUsing)($component, $value);
                        })
                        ->required($isRequired)
                        ->hidden(fn (Get $get): bool => blank($get('overview_model')))
                        ->searchable($this->isSearchable())
                        ->searchDebounce($this->getSearchDebounce())
                        ->searchPrompt($this->getSearchPrompt())
                        ->searchingMessage($this->getSearchingMessage())
                        ->noSearchResultsMessage($this->getNoSearchResultsMessage())
                        ->loadingMessage($this->getLoadingMessage())
                        ->allowHtml($this->isHtmlAllowed())
                        ->optionsLimit($this->getOptionsLimit())
                        ->preload($this->isPreloaded()),
                ]),
        ];
    }

    public function optionsLimit(int|Closure $limit): static
    {
        $this->optionsLimit = $limit;

        return $this;
    }

    public function required(bool|Closure $condition = true): static
    {
        $this->isRequired = $condition;

        return $this;
    }

    public function types(array|Closure $types): static
    {
        $this->types = $types;

        return $this;
    }

    public function getLabel(): string|Htmlable|null
    {
        $label = $this->getLabelFromHasLabel() ?? (string) Str::of($this->getName())
            ->afterLast('.')
            ->kebab()
            ->replace(['-', '_'], ' ')
            ->ucfirst();

        return (is_string($label) && $this->shouldTranslateLabel) ?
            __($label) :
            $label;
    }

    public function getTypes(): array
    {
        $types = [];

        foreach ($this->evaluate($this->types) as $type) {
            $types[$type->getAlias()] = $type;
        }

        return $types;
    }

    public function isRequired(): bool
    {
        return (bool) $this->evaluate($this->isRequired);
    }

    public function getOptionsLimit(): int
    {
        return $this->evaluate($this->optionsLimit);
    }
}
