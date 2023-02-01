<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Textarea;
use Illuminate\View\Component;

class VideoBlock extends AbstractContentBlock
{
    public ?string $embedCode;

    /**
     * Create a new component instance.
     *
     * @param  array|null  $blockData
     */
    public function __construct(?array $blockData)
    {
        $this->embedCode = $blockData['embed_code'] ?? null;
    }

    public static function getName(): string
    {
        return 'video';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-video-camera';
    }

    public static function make(): Block
    {
        return parent::make()
            ->schema([
                Textarea::make('embed_code')
                    ->label(static::getFieldLabel('label'))
                    ->hint(static::getFieldLabel('help'))
                    ->hintIcon('heroicon-s-question-mark-circle')
                    ->rows(2)
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
        return view('components.content-blocks.video');
    }
}
