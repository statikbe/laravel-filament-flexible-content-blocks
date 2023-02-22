<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

interface HasMediaAttributes
{
    /**
     * Sets the default media conversion for the Filament upload field.
     *
     *
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function addFilamentThumbnailMediaConversion(): void;

    /**
     * Returns the image HTML for a given media object.
     */
    public function getImageHtml(?Media $media, string $conversion, string $title = null, array $attributes = []): ?HtmlableMedia;

    /**
     * This function is provided by the spatie/laravel-media-library trait, InteractsWithMedia, but is not defined in an interface.
     * To resolve phpstan errors and make the typing of this package stronger, we define it here.
     *
     * @param  string  $name
     * @return MediaCollection
     */
    public function addMediaCollection(string $name): MediaCollection;
}
