<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockSpatieMediaLibraryFileUpload;

class TextImageBlock extends AbstractContentBlock
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
        return 'text-image';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-view-list';
    }

    public static function make(): Block
    {
        return parent::make()
            ->schema([
                TextInput::make('title')
                    ->label(self::getFieldLabel('title'))
                    ->required(),
                RichEditor::make('content')
                    ->label(self::getFieldLabel('content'))
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->required(),
                BlockSpatieMediaLibraryFileUpload::make('image')
                    ->collection('test')
                    ->label(self::getFieldLabel('image')),
                //https://github.com/filamentphp/filament/issues/1284
            ]);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.blocks.text-image');
    }
}
