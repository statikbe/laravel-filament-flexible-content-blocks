<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Spatie\Translatable\HasTranslations;

/**
 * @property array $translatable
 */
trait HasTranslatedAttributes
{
    use HasTranslations;

    public $translatable = [];

    protected function mergeTranslatable(array $translatableAttributes): void
    {
        $this->translatable = array_merge(
            $this->getTranslatableAttributes(),
            $translatableAttributes
        );
    }
}
