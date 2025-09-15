<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBackgroundColour;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BackgroundColourField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CollapsibleItemData;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

class CollapsibleGroupBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasBackgroundColour;

    const GROUP_TITLE_FIELD = 'group_title';

    const GROUP_INTRO_FIELD = 'group_intro';

    const BACKGROUND_COLOUR_FIELD = 'background_colour';

    const COLLAPSIBLE_ITEMS_FIELD = 'collapsible_items';

    const ITEM_TITLE_FIELD = 'title';

    const ITEM_TEXT_FIELD = 'text';

    const ITEM_IS_OPEN_BY_DEFAULT_FIELD = 'is_open_by_default';

    const ENABLED_TOOLBAR_BUTTONS = [
        'bold',
        'italic',
        'link',
        'bulletList',
        'orderedList',
    ];

    public ?string $groupTitle;

    public ?string $groupIntro;

    public Collection $collapsibleItems;

    public function __construct(HasMedia&HasContentBlocks $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->groupTitle = $blockData[self::GROUP_TITLE_FIELD] ?? null;
        $this->groupIntro = $blockData[self::GROUP_INTRO_FIELD] ?? null;
        $this->backgroundColourType = $blockData[self::BACKGROUND_COLOUR_FIELD] ?? null;
        $this->collapsibleItems = $this->mapToCollapsibleItemsData($blockData[self::COLLAPSIBLE_ITEMS_FIELD]);
    }

    private function mapToCollapsibleItemsData(array $items): Collection
    {
        if (empty($items)) {
            return collect([]);
        }

        return collect($items)->map(function (array $item) {
            return new CollapsibleItemData(
                title: $item[self::ITEM_TITLE_FIELD],
                text: $item[self::ITEM_TEXT_FIELD],
                isOpenByDefault: $item[self::ITEM_IS_OPEN_BY_DEFAULT_FIELD] ?? false,
            );
        });
    }

    public static function getNameSuffix(): string
    {
        return 'collapsible_group';
    }

    public static function getContentSummary(array $state): ?string
    {
        return $state[self::GROUP_TITLE_FIELD] ?? $state[self::GROUP_INTRO_FIELD];
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-bars-arrow-down';
    }

    protected static function makeFilamentSchema(): array|Closure
    {
        return [
            TextInput::make(self::GROUP_TITLE_FIELD)
                ->label(static::getFieldLabel('group_title'))
                ->maxLength(255),
            RichEditor::make(self::GROUP_INTRO_FIELD)
                ->label(static::getFieldLabel('group_intro'))
                ->toolbarButtons(static::getIntroToolbarButtons()),
            BackgroundColourField::create(static::class),
            Repeater::make(self::COLLAPSIBLE_ITEMS_FIELD)
                ->label(static::getFieldLabel('collapsible_items'))
                ->schema([
                    TextInput::make(self::ITEM_TITLE_FIELD)
                        ->label(static::getFieldLabel('item_title'))
                        ->required()
                        ->maxLength(255),
                    RichEditor::make(self::ITEM_TEXT_FIELD)
                        ->label(static::getFieldLabel('item_text'))
                        ->toolbarButtons(static::getItemTextToolbarButtons())
                        ->required(),
                    Toggle::make(self::ITEM_IS_OPEN_BY_DEFAULT_FIELD)
                        ->label(static::getFieldLabel('item_is_open_by_default'))
                        ->default(false),
                ])
                ->itemLabel(function (array $state): ?string {
                    return $state[self::ITEM_TITLE_FIELD];
                })
                ->collapsible()
                ->minItems(1),
        ];
    }

    protected static function getIntroToolbarButtons(): array
    {
        return static::ENABLED_TOOLBAR_BUTTONS;
    }

    protected static function getItemTextToolbarButtons(): array
    {
        return static::ENABLED_TOOLBAR_BUTTONS;
    }
}
