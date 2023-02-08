<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

class QuoteBlock extends AbstractFilamentFlexibleContentBlock
{
    public ?string $quote;

    public ?string $author;

    public function __construct(HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->quote = $blockData['quote'] ?? null;
        $this->author = $blockData['author'] ?? null;
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
                ->label(self::getFieldLabel('quote'))
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->required(),
            TextInput::make('author')
                ->label(self::getFieldLabel('author'))
                ->maxLength(255),
        ];
    }
}
