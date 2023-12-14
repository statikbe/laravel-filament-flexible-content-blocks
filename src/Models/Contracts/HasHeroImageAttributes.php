<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;

/**
 * @property ?string $hero_image_title
 * @property ?string $hero_image_copyright
 */
interface HasHeroImageAttributes
{
    /**
     * Adds a hero image to the media library collection
     */
    public function addHeroImage(string $imagePath): void;

    /**
     * Returns the name of the media library conversion of the hero image
     */
    public function getHeroImageConversionName(): string;

    /**
     * Returns media library collection of hero image
     */
    public function getHeroImageCollection(): string;

    /**
     * Returns the url of the hero image
     */
    public function getHeroImageUrl(?string $conversion = null): ?string;

    /**
     * Returns the rendered html of the hero image
     */
    public function getHeroImageMedia(?string $conversion = null, array $attributes = []): ?HtmlableMedia;

    /**
     * Checks if there is a hero image set.
     */
    public function hasHeroImage(): bool;
}
