<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBackgroundColour;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBlockStyle;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BackgroundColourField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockStyleField;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

class QuoteBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasBackgroundColour;
    use HasBlockStyle;

    public ?string $quote;

    public ?string $author;

    public function __construct(HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->quote = $blockData['quote'] ?? null;
        $this->author = $blockData['author'] ?? null;
        $this->backgroundColourType = $blockData['background_colour'] ?? null;
        $this->setBlockStyle($blockData);
    }

    public static function getNameSuffix(): string
    {
        return 'quote';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-annotation';
    }

    protected static function makeFilamentSchema(): array|Closure
    {
        return [
            RichEditor::make('quote')
                ->label(static::getFieldLabel('quote'))
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->required(),
            TextInput::make('author')
                ->label(static::getFieldLabel('author'))
                ->maxLength(255),
            Grid::make(2)->schema([
                BackgroundColourField::create(static::class),
                BlockStyleField::create(static::class),
            ]),
        ];
    }

    public function getSearchableContent(): array
    {
        $searchable = [];

        $this->addSearchableContent($searchable, $this->quote);
        $this->addSearchableContent($searchable, $this->author);

        return $searchable;
    }
}
