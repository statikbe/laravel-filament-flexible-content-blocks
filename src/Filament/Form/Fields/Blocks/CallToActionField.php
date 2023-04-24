<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Concerns\CanAllowHtml;
use Filament\Forms\Components\Concerns\CanBePreloaded;
use Filament\Forms\Components\Concerns\CanBeSearchable;
use Filament\Forms\Components\Concerns\HasLoadingMessage;
use Filament\Forms\Components\Concerns\HasName;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type\CallToActionType;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

class CallToActionField extends Component
{
    use CanAllowHtml;
    use CanBePreloaded;
    use CanBeSearchable;
    use HasLoadingMessage;
    use HasName;

    const FIELD_CTA_MODEL = 'cta_model';

    const FIELD_ENTRY_ID = 'entry_id';

    const FIELD_URL = 'url';

    const FIELD_ROUTE = 'route';

    const FIELD_BUTTON_STYLE = 'button_style';

    const FIELD_BUTTON_LABEL = 'button_label';

    const FIELD_BUTTON_OPEN_NEW_WINDOW = 'button_open_new_window';

    protected string $view = 'forms::components.fieldset';

    public bool|Closure $isRequired = false;

    protected int|Closure $optionsLimit = 50;

    public array|Closure $types = [];

    public ?string $blockClass = null;

    /**
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
     * @param  class-string<AbstractContentBlock>  $blockClass
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
        $selectedTypeIsRoute = $selectedType?->isRouteType() ?? false;

        return [
            Grid::make(6)
                ->schema([
                    Select::make(static::FIELD_CTA_MODEL)
                        ->columnSpan(2)
                        ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_cta_model'))
                        ->options(array_map(
                            fn (CallToActionType $type): string => $type->getLabel(),
                            $types,
                        ))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set) {
                            $set(static::FIELD_ENTRY_ID, null);
                        }),
                    Select::make(static::FIELD_ENTRY_ID)
                        ->columnSpan(4)
                        ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_entry_id'))
                        ->options($selectedType?->getOptionsUsing)
                        ->getSearchResultsUsing($selectedType?->getSearchResultsUsing)
                        ->getOptionLabelUsing($selectedType?->getOptionLabelUsing)
                        ->required(! $selectedTypeIsUrl && ! $selectedTypeIsRoute && $selectedType)
                        ->hidden(! $selectedType || $selectedTypeIsUrl || $selectedTypeIsRoute)
                        ->searchable($this->isSearchable())
                        ->searchDebounce($this->getSearchDebounce())
                        ->searchPrompt($this->getSearchPrompt())
                        ->searchingMessage($this->getSearchingMessage())
                        ->noSearchResultsMessage($this->getNoSearchResultsMessage())
                        ->loadingMessage($this->getLoadingMessage())
                        ->allowHtml($this->isHtmlAllowed())
                        ->optionsLimit($this->getOptionsLimit())
                        ->preload($this->isPreloaded()),
                    TextInput::make(static::FIELD_URL)
                        ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_model_type_url'))
                        ->columnSpan(4)
                        ->placeholder('https://')
                        ->url()
                        ->required($selectedType && $selectedTypeIsUrl)
                        ->hidden(! $selectedType || ! $selectedTypeIsUrl),
                    Select::make(static::FIELD_ROUTE)
                        ->columnSpan(4)
                        ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_route'))
                        ->options(FilamentFlexibleBlocksConfig::getLinkRoutes())
                        ->required($selectedTypeIsRoute && $selectedType)
                        ->hidden(! $selectedType || ! $selectedTypeIsRoute)
                        ->loadingMessage($this->getLoadingMessage())
                        ->optionsLimit($this->getOptionsLimit())
                        ->preload($this->isPreloaded()),
                ]),
            Grid::make(6)
                ->schema([
                    Select::make(static::FIELD_BUTTON_STYLE)
                        ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_button_style'))
                        ->options(FilamentFlexibleBlocksConfig::getCallToActionButtonsSelectOptions(static::class))
                        ->default(FilamentFlexibleBlocksConfig::getCallToActionButtonDefault(static::class))
                        ->columnSpan(2),
                    TextInput::make(static::FIELD_BUTTON_LABEL)
                        ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_button_label'))
                        ->columnSpan(3),
                    Toggle::make(static::FIELD_BUTTON_OPEN_NEW_WINDOW)
                        ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_button_open_in_new_window'))
                        ->columnSpan(1)
                        ->inline(false),
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
     * @return array<string, string>
     */
    public static function getButtonStyleClasses(string $blockClass): array
    {
        return collect(FilamentFlexibleBlocksConfig::getCallToActionButtonsConfig($blockClass)['options'])
            ->mapWithKeys(fn ($item, $key) => [$key => $item['class']])
            ->toArray();
    }
}
