<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

    use Spatie\Translatable\HasTranslations;

    trait HasTranslatedContentBlocks
    {
        use HasTranslations;
        use HasTranslatedAttributes;
        use HasContentBlocks;

        public function initializeHasTranslatedContentBlocks(): void
        {
            $this->mergeTranslatable(['content_blocks']);
        }
    }
