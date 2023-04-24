<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslatedSEOAttributesTrait
{
    use HasTranslatedAttributesTrait;
    use HasSEOAttributesTrait;

    public function initializeHasTranslatedSEOAttributesTrait(): void
    {
        $this->mergeTranslatable(['seo_title', 'seo_description', 'seo_keywords']);
    }

    public function seoImage(): MorphMany
    {
        return $this->media()
            ->where('collection_name', $this->getSEOImageCollection())
            ->where('custom_properties->locale', app()->getLocale());
    }
}
