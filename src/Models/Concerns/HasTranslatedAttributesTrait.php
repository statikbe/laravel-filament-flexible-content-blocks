<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Spatie\Translatable\HasTranslations;

/**
 * @property array $translatable
 * @property array $translatableMediaCollections
 */
trait HasTranslatedAttributesTrait
{
    use HasTranslations;
    use HasTranslatedMediaTrait;

    /**
     * @var string[]
     */
    public $translatable = [];

    /**
     * @var string[]
     */
    public $translatableMediaCollections = [];

    protected function mergeTranslatable(array $translatableAttributes): void
    {
        $this->translatable = array_merge(
            $this->getTranslatableAttributes(),
            $translatableAttributes
        );
    }


}
