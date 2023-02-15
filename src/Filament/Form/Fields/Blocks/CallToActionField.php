<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

    use Closure;
    use Filament\Forms\Components\Checkbox;
    use Filament\Forms\Components\Component;
    use Filament\Forms\Components\Concerns\CanAllowHtml;
    use Filament\Forms\Components\Concerns\CanBePreloaded;
    use Filament\Forms\Components\Concerns\CanBeSearchable;
    use Filament\Forms\Components\Concerns\HasLoadingMessage;
    use Filament\Forms\Components\Concerns\HasName;
    use Filament\Forms\Components\Grid;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use Illuminate\Contracts\Support\Htmlable;
    use Illuminate\Support\Str;
    use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type\CallToActionType;

    class CallToActionField extends Component
    {
        use CanAllowHtml;
        use CanBePreloaded;
        use CanBeSearchable;
        use HasLoadingMessage;
        use HasName;

        protected string $view = 'forms::components.fieldset';

        public bool|Closure $isRequired = false;

        protected int|Closure $optionsLimit = 50;

        public array|Closure $types = [];

        public ?string $blockClass = null;

        /**
         * @param  string  $name
         * @param  class-string<AbstractContentBlock>  $blockClass
         */
        final public function __construct(string $name, string $blockClass)
        {
            $this->name($name);
            $this->isSearchable = true;
            $this->columns(6);
            $this->blockClass = $blockClass;
        }

        /**
         * @param  string  $name
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @return static
         */
        public static function make(string $name, string $blockClass): static
        {
            $static = app(static::class, [
                'name' => $name,
                'blockClass' => $blockClass,
            ]);
            $static->configure();

            return $static;
        }

        public function getChildComponents(): array
        {
            $types = $this->getTypes();
            $isRequired = $this->isRequired();

            /** @var ?CallToActionType $selectedType */
            $selectedType = $types[$this->evaluate(function (Closure $get): ?string {
                return $get('cta_model');
            })] ?? null;
            $selectedTypeIsUrl = $selectedType?->isUrlType() ?? false;

            $buttonStyles = config("filament-flexible-content-blocks.block_specific.$this->blockClass.call_to_action_buttons.options",
                config('filament-flexible-content-blocks.call_to_action_buttons.options', [])
            );
            $buttonStyleOptions = collect($buttonStyles)
                ->mapWithKeys(fn ($item, $key) => [$key => trans($item['label'])]);

            $buttonStyleDefault = config("filament-flexible-content-blocks.block_specific.$this->blockClass.call_to_action_buttons.default",
                config('filament-flexible-content-blocks.call_to_action_buttons.default', null)
            );

            return [
                Grid::make(6)
                    ->schema([
                        Select::make('cta_model')
                            ->columnSpan(2)
                            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_cta_model'))
                            ->options(array_map(
                                fn (CallToActionType $type): string => $type->getLabel(),
                                $types,
                            ))
                            ->required($isRequired)
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set) {
                                $set('entry_id', null);
                            }),
                        Select::make('entry_id')
                            ->columnSpan(4)
                            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_entry_id'))
                            ->options($selectedType?->getOptionsUsing)
                            ->getSearchResultsUsing($selectedType?->getSearchResultsUsing)
                            ->getOptionLabelUsing($selectedType?->getOptionLabelUsing)
                            ->required($isRequired)
                            ->hidden(! $selectedType || $selectedTypeIsUrl)
                            ->searchable($this->isSearchable())
                            ->searchDebounce($this->getSearchDebounce())
                            ->searchPrompt($this->getSearchPrompt())
                            ->searchingMessage($this->getSearchingMessage())
                            ->noSearchResultsMessage($this->getNoSearchResultsMessage())
                            ->loadingMessage($this->getLoadingMessage())
                            ->allowHtml($this->isHtmlAllowed())
                            ->optionsLimit($this->getOptionsLimit())
                            ->preload($this->isPreloaded()),
                        TextInput::make('url')
                            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_model_type_url'))
                            ->columnSpan(4)
                            ->placeholder('https://')
                            ->hidden(! $selectedType || ! $selectedTypeIsUrl),
                    ]),
                Grid::make(6)
                    ->schema([
                        Select::make('button_style')
                            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_button_style'))
                            ->options($buttonStyleOptions)
                            ->default($buttonStyleDefault)
                            ->columnSpan(2),
                        TextInput::make('button_label')
                            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_button_label'))
                            ->columnSpan(3),
                        Checkbox::make('button_open_new_window')
                            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_button_open_in_new_window'))
                            ->columnSpan(1),
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

        /**
         * @param  string  $blockClass
         * @return array<string, string>
         */
        public static function getButtonStyleClasses(string $blockClass): array
        {
            $buttonStyles = config("filament-flexible-content-blocks.block_specific.$blockClass.call_to_action_buttons.options",
                config('filament-flexible-content-blocks.call_to_action_buttons.options', [])
            );

            return collect($buttonStyles)
                ->mapWithKeys(fn ($item, $key) => [$key => $item['class']])
                ->toArray();
        }
    }
