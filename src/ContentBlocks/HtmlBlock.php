<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\Textarea;
use Filament\Support\Icons\Heroicon;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

class HtmlBlock extends AbstractFilamentFlexibleContentBlock
{
    public ?string $content;

    /**
     * Create a new component instance.
     */
    public function __construct(HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->content = $blockData['content'] ?? null;
    }

    public static function getNameSuffix(): string
    {
        return 'html';
    }

    public static function getIcon(): Heroicon|string
    {
        return Heroicon::CodeBracket;
    }

    /**
     * {@inheritDoc}
     */
    protected static function makeFilamentSchema(): array|Closure
    {
        return [
            Textarea::make('content')
                ->label(static::getFieldLabel('label'))
                ->hint(static::getFieldLabel('help'))
                ->hintIcon(Heroicon::QuestionMarkCircle)
                ->rows(5)
                ->required(),
        ];
    }

    public function getSearchableContent(): array
    {
        $searchable = [];

        $this->addSearchableContent($searchable, $this->content);

        return $searchable;
    }
}
