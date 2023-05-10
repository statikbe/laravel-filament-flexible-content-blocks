<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Statikbe\FilamentFlexibleContentBlocks\Events\SlugChanged;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks;

trait HasTranslatedSlugAttributeTrait
{
    use HasTranslatableSlug;
    use HasTranslatedAttributesTrait;

    public function initializeHasTranslatedSlugAttributeTrait(): void
    {
        $this->mergeTranslatable(['slug']);
    }

    protected static function bootHasTranslatedSlugAttributeTrait(): void
    {
        //dispatch event when slug changes for published models:
        static::updating(function (self $record) {
            $newSlugs = $record->getTranslations('slug');
            $existingSlugs = $record->getOriginal('slug');
            $changedSlugs = [];
            foreach ($existingSlugs as $locale => $existingSlug) {
                if (! isset($newSlugs[$locale])) {
                    $changedSlugs[] = [
                        'locale' => $locale,
                        'oldSlug' => $existingSlug,
                        'newSlug' => null,
                    ];
                } elseif ($newSlugs[$locale] !== $existingSlug) {
                    $changedSlugs[] = [
                        'locale' => $locale,
                        'oldSlug' => $existingSlug,
                        'newSlug' => $newSlugs[$locale],
                    ];
                }
            }

            if (! empty($changedSlugs)) {
                $published = true;
                if (method_exists($record, 'isPublishedForDates')) {
                    $published = $record->isPublishedForDates($record->getOriginal('publishing_begins_at'), $record->getOriginal('publishing_ends_at'));
                }

                //dispatch event:
                SlugChanged::dispatch($record, $changedSlugs, $published);
            }
        });
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

    public function getLocalizedRouteKey($locale)
    {
        return $this->getTranslation('slug', $locale);
    }

    /**
     * This method is overwritten to make filament resolve the model with a translated slug key.
     * {@inheritDoc}
     */
    public function resolveRouteBindingQuery($query, $value, $field = null): Builder|Relation
    {
        $field = $field ?? $this->getRouteKeyName();

        if (! $this->isTranslatableAttribute($field)) {
            return parent::resolveRouteBindingQuery($query, $value, $field);
        }

        return $query->where(function (Builder $query) use ($field, $value) {
            foreach (array_values(FilamentFlexibleContentBlocks::getLocales()) as $locale) {
                $query->orWhere("{$field}->{$locale}", $value);
            }

            return $query;
        });
    }
}
