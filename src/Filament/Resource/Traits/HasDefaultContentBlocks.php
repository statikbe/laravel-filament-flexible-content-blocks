<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Resource\Traits;

use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\HtmlBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\VideoBlock;

/**
 * An implementation of the HasFilamentContentBlocks interface for all blocks offered by the library.
 */
trait HasDefaultContentBlocks
{
    use HasContentBlocks;

    /**
     * {@inheritDoc}
     */
    public static function getContentBlockClasses(): array
    {
        return [
            TextBlock::class,
            VideoBlock::class,
            HtmlBlock::class,
            TextImageBlock::class,
        ];
    }
}
