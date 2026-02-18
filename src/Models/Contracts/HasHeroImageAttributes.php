<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;

/**
 * @property ?string $hero_image_title
 * @property ?string $hero_image_copyright
 */
interface HasHeroImageAttributes
{
    /**
     * Returns the media-library relation to the hero image.
     */
    public function heroImage(): MorphMany;

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

    /**
     * Returns the title of the hero image
     */
    public function getHeroImageTitle(): ?string;

    /**
     * Returns the copyright description of the hero image
     */
    public function getHeroImageCopyright(): ?string;
}
