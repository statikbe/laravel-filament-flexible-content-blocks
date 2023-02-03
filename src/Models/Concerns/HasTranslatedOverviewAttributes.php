<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedOverviewAttributes
{
    use HasTranslatedAttributes;
    use HasOverviewAttributes;

    public function initializeHasTranslatedOverviewAttributes(): void
    {
        $this->mergeTranslatable(['overview_title', 'overview_description']);
    }
}
