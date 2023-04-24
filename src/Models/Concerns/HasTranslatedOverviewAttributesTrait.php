<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslatedOverviewAttributesTrait
{
    use HasTranslatedAttributesTrait;
    use HasOverviewAttributesTrait;

    public function initializeHasTranslatedOverviewAttributesTrait(): void
    {
        $this->mergeTranslatable(['overview_title', 'overview_description']);
    }

    public function overviewImage(): MorphMany
    {
        return $this->media()
            ->where('collection_name', $this->getOverviewImageCollection())
            ->where('custom_properties->locale', app()->getLocale());
    }
}
