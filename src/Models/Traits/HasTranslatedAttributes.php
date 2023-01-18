<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

use Spatie\Translatable\HasTranslations;

/**
 * @property array $translatable
 */
trait HasTranslatedAttributes
{
    use HasTranslations;

    protected function mergeTranslatable(array $translatableAttributes): void
    {
        $this->translatable = array_merge(
            $this->getTranslatableAttributes(),
            $translatableAttributes
        );
    }
}
