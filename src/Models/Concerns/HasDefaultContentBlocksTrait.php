<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

/**
 * @mixin HasContentBlocks
 */
trait HasDefaultContentBlocksTrait
{
    use HasContentBlocksTrait;

    /**
     * {@inheritDoc}
     */
    public static function registerContentBlocks(): array
    {
        return FilamentFlexibleBlocksConfig::getDefaultFlexibleBlocks();
    }
}
