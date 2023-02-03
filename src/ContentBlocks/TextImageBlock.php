<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockSpatieMediaLibraryFileUpload;

class TextImageBlock extends AbstractContentBlock
{
    public ?string $title;

    public ?string $content;

    public ?string $image;

    /**
     * Create a new component instance.
     *
     * @param  array|null  $blockData
     */
    public function __construct(?array $blockData)
    {
        $this->title = $blockData['title'] ?? null;
        $this->content = $blockData['content'] ?? null;
        $this->image = $blockData['image'] ?? null;
    }

    public static function getName(): string
    {
        return 'text-image';
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
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('filament-flexible-content-blocks::content-blocks.text-image');
    }
}
