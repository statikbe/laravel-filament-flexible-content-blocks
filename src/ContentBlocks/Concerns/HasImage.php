<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

trait HasImage
{
    /**
     * Returns the media with UUID $imageId
     */
    protected function getMedia(?string $imageId, ?string $collection = null): ?Media
    {
        if (! $imageId) {
            return null;
        }

        /* @var HasMedia $recordWithMedia */
        $recordWithMedia = $this->record;

        return $recordWithMedia->getMedia($collection ?? static::getName(), function (Media $media) use ($imageId) {
            return $media->uuid === $imageId;
        })->first();
    }

    /**
     * Returns an HTML view of the first image
     */
    protected function getHtmlableMedia(?string $imageId, string $conversion, ?string $imageTitle, array $attributes = [], ?string $collection = null): ?HtmlableMedia
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
    protected function getMediaUrl(string $imageId, ?string $collection = null, ?string $conversion = null): ?string
    {
        $media = $this->getMedia($imageId, $collection);

        return $media?->getFullUrl();
    }

    protected static function addCropImageConversion(HasMedia&HasMediaAttributes $record, int $width, int $height): Conversion
    {
        return static::addImageConversion($record, self::CONVERSION_CROP, Manipulations::FIT_CROP, $width, $height);
    }

    protected static function addContainImageConversion(HasMedia&HasMediaAttributes $record, int $width, int $height): Conversion
    {
        return static::addImageConversion($record, self::CONVERSION_CONTAIN, Manipulations::FIT_CONTAIN, $width, $height);
    }

    protected static function addImageConversion(HasMedia&HasMediaAttributes $record, string $conversionName, string $fitType, int $width, int $height): Conversion
    {
        $conversion = $record->addMediaConversion($conversionName)
            ->withResponsiveImages()
            ->fit($fitType, $width, $height)
            ->format(Manipulations::FORMAT_WEBP);
        FilamentFlexibleBlocksConfig::mergeConfiguredFlexibleBlockImageConversion(static::class, static::getName(), $conversionName, $conversion);
        FilamentFlexibleBlocksConfig::addExtraFlexibleBlockImageConversions(static::class, static::getName(), $record);

        return $conversion;
    }

    /**
     * Return all image UUIDs of this block.
     *
     * @return array<string>
     */
    abstract public function getImageUuids(): array;
}
