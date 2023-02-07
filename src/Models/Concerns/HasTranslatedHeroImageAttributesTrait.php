<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedHeroImageAttributesTrait
{
    use HasTranslatedAttributesTrait;
    use HasHeroImageAttributesTrait;

    public function initializeHasTranslatedHeroImageAttributesTrait(): void
    {
        $this->mergeTranslatable(['hero_image_title', 'hero_image_copyright']);
    }
}
