<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

trait HasTranslatedContentBlocks
{
    use HasTranslatedAttributes;
    use HasContentBlocks;

    public function initializeHasTranslatedContentBlocks(): void
    {
        $this->mergeTranslatable(['content_blocks']);
    }

    /**
     * Overrides default getter, to be able to json decode the translated content blocks.
     * By default spatie/laravel-translatable does not parse the translated JSON to an array, but just returns a string.
     *
     * @param  string|null  $translatedContentBlocks
     * @return array
     */
    public function getContentBlocksAttribute(?string $translatedContentBlocks): array
    {
        if ($translatedContentBlocks) {
            return json_decode($translatedContentBlocks, true);
        } else {
            return [];
        }
    }
}
