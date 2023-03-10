<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property string $slug
 */
trait HasSlugAttributeTrait
{
    use HasSlug;

    public function initializeHasSlugAttributeTrait(): void
    {
        $this->mergeFillable(['slug']);
    }

    /**
     * Get the options for generating the slug.
     *
     * @property string|null $slug
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
