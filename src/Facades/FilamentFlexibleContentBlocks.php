<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks
 */
class FilamentFlexibleContentBlocks extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::class;
    }
}
