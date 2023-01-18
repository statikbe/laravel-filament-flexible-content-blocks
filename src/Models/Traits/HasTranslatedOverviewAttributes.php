<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

    use Spatie\Translatable\HasTranslations;

    trait HasTranslatedOverviewAttributes
    {
        use HasTranslations;
        use HasTranslatedAttributes;
        use HasOverviewAttributes;

        public function initializeHasTranslatedOverviewAttributes(): void
        {
            $this->mergeTranslatable(['overview_title', 'overview_description']);
        }
    }
