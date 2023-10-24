<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslatedHeroImageAttributesTrait
{
    use HasHeroImageAttributesTrait;
    use HasTranslatedAttributesTrait;

    public function initializeHasTranslatedHeroImageAttributesTrait(): void
    {
        $this->mergeTranslatable(['hero_image_title', 'hero_image_copyright']);
        $this->mergeTranslatableMediaCollection([$this->getHeroImageCollection()]);
    }

    public function heroImage(): MorphMany
    {
        return $this->media()
            ->where('collection_name', $this->getHeroImageCollection())
            ->where('custom_properties->locale', app()->getLocale());
    }
}
