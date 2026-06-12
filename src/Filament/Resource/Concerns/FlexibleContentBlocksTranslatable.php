<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Resource\Concerns;

use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Statikbe\FilamentFlexibleContentBlocks\Facades\FilamentFlexibleContentBlocks;

trait FlexibleContentBlocksTranslatable
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return FilamentFlexibleContentBlocks::getLocales();
    }
}
