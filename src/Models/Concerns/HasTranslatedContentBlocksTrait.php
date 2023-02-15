<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedContentBlocksTrait
{
    use HasTranslatedAttributesTrait;
    use HasContentBlocksTrait;

    public function initializeHasTranslatedContentBlocksTrait(): void
    {
        $this->mergeTranslatable(['content_blocks']);
    }

    /**
     * Overrides default getter, to be able to json decode the translated content blocks.
     * By default spatie/laravel-translatable does not parse the translated JSON to an array, but just returns a string.
     */
    public function getContentBlocksAttribute(string|array|null $translatedContentBlocks): array
    {
        if (is_array($translatedContentBlocks)) {
            return $translatedContentBlocks;
        } elseif ($translatedContentBlocks) {
            return json_decode($translatedContentBlocks, true);
        } else {
            return [];
        }
    }
}
