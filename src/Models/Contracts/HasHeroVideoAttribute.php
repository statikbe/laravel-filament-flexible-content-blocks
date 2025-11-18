<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

/**
 * @property ?string $hero_video_url
 */
interface HasHeroVideoAttribute
{
    /**
     * Returns the url to the video that will be embedded in the hero.
     */
    public function getHeroVideoUrl(): ?string;

    /**
     * Checks if a video url is set.
     */
    public function hasHeroVideoUrl(): bool;
}
