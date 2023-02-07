<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedOverviewAttributesTrait
{
    use HasTranslatedAttributesTrait;
    use HasOverviewAttributesTrait;

    public function initializeHasTranslatedOverviewAttributesTrait(): void
    {
        $this->mergeTranslatable(['overview_title', 'overview_description']);
    }
}
