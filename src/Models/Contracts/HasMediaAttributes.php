<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
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
}
