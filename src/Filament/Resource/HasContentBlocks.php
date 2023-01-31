<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Resource;

use Filament\Forms\Components\Builder\Block;

interface HasContentBlocks
{
    /**
     * Returns an array of all allowed content blocks for this resource. The order will be the order in which they
     * are presented to the user.
     *
     * @return array<Block>
     */
    public static function getContentBlocks(): array;
}
