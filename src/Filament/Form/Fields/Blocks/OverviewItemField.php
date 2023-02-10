<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

    use Closure;
    use Filament\Forms\Components\Component;
    use Filament\Forms\Components\Concerns\CanAllowHtml;
    use Filament\Forms\Components\Concerns\CanBePreloaded;
    use Filament\Forms\Components\Concerns\CanBeSearchable;
    use Filament\Forms\Components\Concerns\HasLoadingMessage;
    use Filament\Forms\Components\Concerns\HasName;
    use Filament\Forms\Components\Select;
    use Illuminate\Contracts\Support\Htmlable;
    use Illuminate\Support\Str;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Overview\OverviewType;

    class OverviewItemField extends Component
    {
        use CanAllowHtml;
        use CanBePreloaded;
        use CanBeSearchable;
        use HasLoadingMessage;
        use HasName;

        protected string $view = 'forms::components.grid';

        public bool|Closure $isRequired = false;

        protected int|Closure $optionsLimit = 50;

        public array|Closure $types = [];

        final public function __construct(string $name)
        {
            $this->name($name);
            $this->isSearchable = true;
            $this->columns(6);
        }

        public static function make(string $name): static
        {
            $static = app(static::class, ['name' => $name]);
            $static->configure();

            return $static;
        }

        public function getChildComponents(): array
        {
            $types = $this->getTypes();
            $isRequired = $this->isRequired();

            /** @var ?OverviewType $selectedType */
            $selectedType = $types[$this->evaluate(function (Closure $get): ?string {
                return $get('overview_model');
            })] ?? null;

            return [
                Select::make('overview_model')
                    ->columnSpan(2)
                    ->label($this->getLabel())
                    ->disableLabel()
                    ->options(array_map(
                        fn (OverviewType $type): string => $type->getLabel(),
                        $types,
                    ))
                    ->required($isRequired)
                    ->reactive()
                    ->afterStateUpdated(function (Closure $set) {
                        $set('overview_id', null);
                    }),
                Select::make('overview_id')
                    ->columnSpan(4)
                    ->label($selectedType?->getLabel())
                    ->disableLabel()
                    ->options($selectedType?->getOptionsUsing)
                    ->getSearchResultsUsing($selectedType?->getSearchResultsUsing)
                    ->getOptionLabelUsing($selectedType?->getOptionLabelUsing)
                    ->required($isRequired)
                    ->hidden(! $selectedType)
                    ->searchable($this->isSearchable())
                    ->searchDebounce($this->getSearchDebounce())
                    ->searchPrompt($this->getSearchPrompt())
                    ->searchingMessage($this->getSearchingMessage())
                    ->noSearchResultsMessage($this->getNoSearchResultsMessage())
                    ->loadingMessage($this->getLoadingMessage())
                    ->allowHtml($this->isHtmlAllowed())
                    ->optionsLimit($this->getOptionsLimit())
                    ->preload($this->isPreloaded()),
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
            $label = parent::getLabel() ?? (string) Str::of($this->getName())
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
