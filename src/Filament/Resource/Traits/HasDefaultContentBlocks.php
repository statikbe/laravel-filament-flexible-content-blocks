<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Resource\Traits;

use Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks\HtmlBlock;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks\TextBlock;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks\VideoBlock;

/**
 * An implementation of the HasContentBlocks interface for all blocks offered by the library.
 */
trait HasDefaultContentBlocks
{
    public static function getContentBlocks(): array
    {
        return [
            TextBlock::make(),
            VideoBlock::make(),
            HtmlBlock::make(),
            TextImageBlock::make(),
        ];
    }
}
