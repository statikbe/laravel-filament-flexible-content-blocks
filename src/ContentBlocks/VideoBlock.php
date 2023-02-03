<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Filament\Forms\Components\Textarea;

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

    /**
     * {@inheritDoc}
     */
    protected static function makeFilamentSchema(): array|\Closure
    {
        return [
            Textarea::make('embed_code')
                ->label(static::getFieldLabel('label'))
                ->hint(static::getFieldLabel('help'))
                ->hintIcon('heroicon-s-question-mark-circle')
                ->rows(2)
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
        return view('filament-flexible-content-blocks::content-blocks.video');
    }
}
