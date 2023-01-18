<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

trait HasTranslatedOverviewAttributes
{
    use HasTranslatedAttributes;
    use HasOverviewAttributes;

    public function initializeHasTranslatedOverviewAttributes(): void
    {
        $this->mergeTranslatable(['overview_title', 'overview_description']);
    }
}
