<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasImage
{
    /**
     * Returns the media with UUID $imageId
     */
    protected function getMedia(string $imageId, ?string $collection = null): ?Media
    {
        /* @var HasMedia $recordWithMedia */
        $recordWithMedia = $this->record;

        return $recordWithMedia->getMedia($collection ?? static::getName(), function (Media $media) use ($imageId) {
            return $media->uuid === $imageId;
        })->first();
    }

    /**
     * Returns an HTML view of the first image
     *
     * @param  string  $imageId,
     */
    protected function getHtmlableMedia(string $imageId, string $conversion, ?string $imageTitle, array $attributes = [], ?string $collection = null): ?HtmlableMedia
    {
        $media = $this->getMedia($imageId);
        $html = null;

        if ($media) {
            $html = $media->img()
                ->conversion($conversion);

            if ($imageTitle) {
                $attributes = array_merge([
                    'title' => $imageTitle,
                    'alt' => $imageTitle,
                ], $attributes);
            }

            $html->attributes($attributes);
        }

        return $html;
    }

    /**
     * Returns the image url for the given UUID.
     */
    protected function getMediaUrl(string $imageId, ?string $collection = null): ?string
    {
        $media = $this->getMedia($imageId, $collection);

        return $media?->getFullUrl();
    }
}
