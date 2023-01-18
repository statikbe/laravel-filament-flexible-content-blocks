<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

trait HasTranslatedSlugAttribute
{
    use HasTranslations;
    use HasTranslatableSlug;
    use HasTranslatedAttributes;

    public function initializeHasTranslatedSlugAttribute(): void
    {
        $this->mergeTranslatable(['slug']);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
