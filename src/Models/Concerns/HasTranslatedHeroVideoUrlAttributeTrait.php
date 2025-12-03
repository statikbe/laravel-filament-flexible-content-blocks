<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedHeroVideoUrlAttributeTrait
{
    use HasHeroVideoAttributeTrait;
    use HasTranslatedAttributesTrait;

    public function initializeHasTranslatedHeroVideoUrlAttributeTrait(): void
    {
        $this->mergeTranslatable(['hero_video_url']);
    }
}
