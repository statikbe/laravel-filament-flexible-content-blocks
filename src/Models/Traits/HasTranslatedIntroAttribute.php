<?php

    namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

    trait HasTranslatedIntroAttribute {
        use HasTranslatedAttributes;
        use HasIntroAttribute;

        public function initializeHasTranslatedIntroAttribute(): void
        {
            $this->mergeTranslatable(['intro']);
        }
    }
