<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBackgroundColour;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BackgroundColourField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CollapsibleItemData;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\FlexibleRichEditorField;
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

    public ?string $groupTitle;

    public ?string $groupIntro;

    public Collection $collapsibleItems;

    public function __construct(HasMedia&HasContentBlocks $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->groupTitle = $blockData[static::GROUP_TITLE_FIELD] ?? null;
        $this->groupIntro = $blockData[static::GROUP_INTRO_FIELD] ?? null;
        $this->backgroundColourType = $blockData[static::BACKGROUND_COLOUR_FIELD] ?? null;
        $this->collapsibleItems = $this->mapToCollapsibleItemsData($blockData[static::COLLAPSIBLE_ITEMS_FIELD]);
    }

    private function mapToCollapsibleItemsData(array $items): Collection
    {
        return collect($items)->map(function (array $item) {
            return new CollapsibleItemData(
                title: $item[static::ITEM_TITLE_FIELD],
                text: $item[static::ITEM_TEXT_FIELD],
                isOpenByDefault: $item[static::ITEM_IS_OPEN_BY_DEFAULT_FIELD] ?? false,
            );
        });
    }

    public static function getNameSuffix(): string
    {
        return 'collapsible_group';
    }

    public static function getContentSummary(array $state): ?string
    {
        return $state[static::GROUP_TITLE_FIELD] ?? FlexibleRichEditorField::toPlainText($state[static::GROUP_INTRO_FIELD], static::class);
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-bars-arrow-down';
    }

    protected static function makeFilamentSchema(): array|Closure
    {
        return [
            TextInput::make(static::GROUP_TITLE_FIELD)
                ->label(static::getFieldLabel('group_title'))
                ->maxLength(255),
            FlexibleRichEditorField::create(static::GROUP_INTRO_FIELD, static::class)
                ->label(static::getFieldLabel('group_intro')),
            BackgroundColourField::create(static::class),
            Repeater::make(static::COLLAPSIBLE_ITEMS_FIELD)
                ->label(static::getFieldLabel('collapsible_items'))
                ->schema([
                    TextInput::make(static::ITEM_TITLE_FIELD)
                        ->label(static::getFieldLabel('item_title'))
                        ->required()
                        ->maxLength(255),
                    FlexibleRichEditorField::create(static::ITEM_TEXT_FIELD, static::class)
                        ->label(static::getFieldLabel('item_text'))
                        ->required(),
                    Toggle::make(static::ITEM_IS_OPEN_BY_DEFAULT_FIELD)
                        ->label(static::getFieldLabel('item_is_open_by_default'))
                        ->default(false),
                ])
                ->itemLabel(function (array $state): ?string {
                    return $state[static::ITEM_TITLE_FIELD];
                })
                ->collapsible()
                ->minItems(1),
        ];
    }
}
