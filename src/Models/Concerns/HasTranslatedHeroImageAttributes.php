<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedHeroImageAttributes
{
    use HasTranslatedAttributes;
    use HasHeroImageAttributes;

    public function initializeHasTranslatedHeroImageAttributes(): void
    {
        $this->mergeTranslatable(['hero_image_title', 'hero_image_copyright']);
    }
}
