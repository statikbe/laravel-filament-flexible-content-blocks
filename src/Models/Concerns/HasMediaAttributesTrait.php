<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

/**
 * @mixin HasMedia
 * @mixin HasMediaAttributes
 */
trait HasMediaAttributesTrait
{
    /**
     * Sets the default media conversion for the Filament upload field.
     *
     *
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function addFilamentThumbnailMediaConversion(): void
    {
        $this->addMediaConversion('thumbnail')
            ->fit(Manipulations::FIT_CONTAIN, 400, 400);
    }

    /**
     * Returns the first media for the given collection. First, we check if there is a locale specific version.
     */
    public function getImageMedia(string $collection): ?Media
    {
        $media = $this->getFirstMedia($collection, ['locale' => app()->getLocale()]);
        if (! $media) {
            $media = $this->getFirstMedia($collection);
        }

        return $media;
    }

    /**
     * Returns the image HTML for a given media object.
     */
    public function getImageHtml(?Media $media, string $conversion, string $title = null, array $attributes = []): ?HtmlableMedia
    {
        $html = null;

        if ($media) {
            $html = $media->img()
                ->conversion($conversion);

            if ($title) {
                $attributes = array_merge([
                    'title' => $title,
                    'alt' => $title,
                ], $attributes);
            }
            $html->attributes($attributes);
        }

        return $html;
    }
}
