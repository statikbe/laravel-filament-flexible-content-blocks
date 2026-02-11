<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBackgroundColour;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBlockStyle;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BackgroundColourField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockStyleField;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

/**
 * @deprecated This block has very similar or fewer functionality than other blocks.
 * This block has been removed from the default blocks in the configuration.
 * Use TextImageBlock or CallToActionBlock
 */
class TextBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasBackgroundColour;
    use HasBlockStyle;

    public ?string $content;

    public ?string $title;

    /**
     * Create a new component instance.
     */
    public function __construct(Model&HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->content = $blockData['content'] ?? null;
        $this->backgroundColourType = $blockData['background_colour'] ?? null;
        $this->title = $blockData['title'] ?? null;
        $this->setBlockStyle($blockData);
    }

    public static function getNameSuffix(): string
    {
        return 'text';
    }

    public static function getContentSummary(array $state): ?string
    {
        return $state['title'] ?? $state['content'];
    }

    public static function getIcon(): Heroicon|string
    {
        return Heroicon::Bars3BottomLeft;
    }

    /**
     * {@inheritDoc}
     */
    protected static function makeFilamentSchema(): array|Closure
    {
        return [
            TextInput::make('title')
                ->label(static::getFieldLabel('title')),
            RichEditor::make('content')
                ->label(static::getFieldLabel('label'))
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->required(),
            Grid::make(2)->schema([
                BackgroundColourField::create(static::class),
                BlockStyleField::create(static::class),
            ]),
        ];
    }

    public function getSearchableContent(): array
    {
        $searchable = [];

        $this->addSearchableContent($searchable, $this->title);
        $this->addSearchableContent($searchable, $this->content);

        return $searchable;
    }
}
