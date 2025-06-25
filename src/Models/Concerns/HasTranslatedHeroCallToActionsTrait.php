<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedHeroCallToActionsTrait
{
    use HasHeroCallToActionsTrait;
    use HasTranslatedAttributesTrait;

    public function initializeHasTranslatedHeroCallToActionsTrait(): void
    {
        $this->mergeTranslatable(['hero_call_to_actions']);
    }
}
