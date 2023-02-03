<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

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

    /**
     * {@inheritDoc}
     */
    protected static function makeFilamentSchema(): array|\Closure
    {
        return [
            RichEditor::make('content')
                ->label(self::getFieldLabel('label'))
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->required(),
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('filament-flexible-content-blocks::content-blocks.text');
    }
}
