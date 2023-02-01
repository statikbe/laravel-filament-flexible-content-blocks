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

    public static function getIcon(): string
    {
        return 'heroicon-o-view-list';
    }

    public static function make(): Block
    {
        return parent::make()
            ->schema([
                RichEditor::make('content')
                    ->label(self::getFieldLabel('label'))
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->required(),
            ]);
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
