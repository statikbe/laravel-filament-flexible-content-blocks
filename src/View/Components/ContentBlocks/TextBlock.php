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

    public static function getFilamentBlock(): Block
    {
        return parent::getFilamentBlock()
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.content_block_text.title'))
            ->schema([
                RichEditor::make('content')
                    ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.content_block_text.content_lbl'))
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
        return view('components.content-blocks.text');
    }
}
