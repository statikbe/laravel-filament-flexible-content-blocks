<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns;

use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

trait HasImageWidth
{
    public ?string $imageWidth;

    public function getImageWidthClass(): ?string
    {
        return FilamentFlexibleBlocksConfig::getImageWidthClass(static::class, $this->imageWidth);
    }
}
