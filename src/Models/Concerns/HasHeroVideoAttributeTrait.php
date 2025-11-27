<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasHeroVideoAttribute;

/**
 * @mixin HasHeroVideoAttribute
 */
trait HasHeroVideoAttributeTrait
{
    public function initializeHasHeroVideoAttributeTrait(): void
    {
        $this->mergeFillable(['hero_video_url']);
    }

    public function getHeroVideoUrl(): ?string
    {
        return $this->hasAttribute('hero_video_url') ? $this->hero_video_url : null;
    }

    public function hasHeroVideoUrl(): bool
    {
        return $this->hasAttribute('hero_video_url') && ! empty(trim($this->hero_video_url));
    }
}
