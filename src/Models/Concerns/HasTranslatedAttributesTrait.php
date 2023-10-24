<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Spatie\Translatable\HasTranslations;

/**
 * @property array $translatable
 */
trait HasTranslatedAttributesTrait
{
    use HasTranslations;

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

    public function mergeTranslatableMediaCollection(array $translatableMediaCollections): void
    {
        $this->translatableMediaCollections = array_merge(
            $this->getTranslatableMediaCollections(),
            $translatableMediaCollections
        );
    }

    /**
     * @return string[]
     */
    public function getTranslatableMediaCollections(): array
    {
        return $this->translatableMediaCollections;
    }
}
