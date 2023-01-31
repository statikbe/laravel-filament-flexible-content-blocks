<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\RichEditor;

class TextBlock extends AbstractContentBlock
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
        return 'text';
    }

    public static function make(): Block
    {
        return Block::make(self::getName())
            ->label(self::getLabel())
            ->schema([
                RichEditor::make('content')
                    ->label(self::getFieldLabel('content'))
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->required(),
            ])->icon('heroicon-o-view-list');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.blocks.text');
    }
}
