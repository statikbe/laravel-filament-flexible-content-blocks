<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBackgroundColour;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBlockStyle;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BackgroundColourField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockStyleField;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

class TextBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasBackgroundColour;
    use HasBlockStyle;

    public ?string $content;

    public ?string $title;

    /**
     * Create a new component instance.
     */
    public function __construct(HasContentBlocks&HasMedia $record, ?array $blockData)
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

    public static function getIcon(): string
    {
        return 'heroicon-o-bars-3-bottom-left';
    }

    /**
     * {@inheritDoc}
     */
    protected static function makeFilamentSchema(): array|\Closure
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
