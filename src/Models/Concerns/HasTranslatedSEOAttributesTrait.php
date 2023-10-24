<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslatedSEOAttributesTrait
{
    use HasSEOAttributesTrait;
    use HasTranslatedAttributesTrait;

    public function initializeHasTranslatedSEOAttributesTrait(): void
    {
        $this->mergeTranslatable(['seo_title', 'seo_description', 'seo_keywords']);
        $this->mergeTranslatableMediaCollection([$this->getSEOImageCollection()]);
    }

    public function seoImage(): MorphMany
    {
        return $this->media()
            ->where('collection_name', $this->getSEOImageCollection())
            ->where('custom_properties->locale', app()->getLocale());
    }
}
