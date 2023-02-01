<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Textarea;

class HtmlBlock extends AbstractContentBlock
{
    public ?string $content;

    /**
     * Create a new component instance.
     *
     * @param  array|null  $blockData
     */
    public function __construct(?array $blockData)
    {
        $this->content = $blockData['content'] ?? null;
    }

    public static function getName(): string
    {
        return 'html';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-code';
    }

    public static function make(): Block
    {
        return parent::make()
            ->schema([
                Textarea::make('content')
                    ->label(static::getFieldLabel('label'))
                    ->hint(static::getFieldLabel('help'))
                    ->hintIcon('heroicon-s-question-mark-circle')
                    ->rows(5)
                    ->required(),
            ]);
    }

    public function render()
    {
        return view('components.content-blocks.html');
    }
}
