<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Statikbe\FilamentFlexibleContentBlocks\Events\SlugChanged;

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

    protected static function bootHasSlugAttributeTrait(): void
    {
        //dispatch event when slug changes for published models:
        static::updating(function (self $record) {
            $newSlug = $record->slug;
            $existingSlug = $record->getOriginal('slug');
            $changed = ($newSlug !== $existingSlug);

            if($changed){
                $published = true;
                if(method_exists($this, 'isPublishedForDates')){
                    $published = $this->isPublishedForDates($this->getOriginal('publishing_begins_at'), $this->getOriginal('publishing_ends_at'));
                }

                //dispatch event:
                SlugChanged::dispatch($this, $published);
            }
        });
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
