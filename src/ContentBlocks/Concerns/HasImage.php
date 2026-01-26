<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Enums\ImageFormat;

trait HasImage
{
    /**
     * Returns the first media with block UUID $blockId
     */
    protected function getMedia(?string $blockId, ?string $collection = null): ?Media
    {
        return $this->getAllMedia($blockId, $collection)?->first();
    }

    protected function getAllMedia(?string $blockId, ?string $collection = null): ?MediaCollection
    {
        if (! $blockId) {
            $blockId = $this->getBlockId();
        }

        if (! $blockId) {
            return null;
        }

        /* @var HasMedia&InteractsWithMedia $recordWithMedia */
        $recordWithMedia = $this->record;

        /** @var MediaCollection $media */
        $media = $recordWithMedia->getMedia($collection ?? static::getName(), [
            'block' => $blockId,
        ]);

        return $media;
    }

    public function hasImage(?string $blockId = null, ?string $collection = null): bool
    {
        if (! $blockId) {
            $blockId = $this->getBlockId();
        }

        /* @var HasMedia&InteractsWithMedia $recordWithMedia */
        $recordWithMedia = $this->record;

        // @phpstan-ignore-next-line    Discrepancy between the spatie/medialibrary interface and trait.
        return $recordWithMedia->hasMedia($collection ?? static::getName(), [
            'block' => $blockId,
        ]);
    }

    /**
     * Returns an HTML view of the first image
     */
    protected function getHtmlableMedia(?string $blockId, string $conversion, ?string $imageTitle, array $attributes = [], ?string $collection = null): ?HtmlableMedia
    {
        $media = $this->getMedia($blockId);
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
    protected function getMediaUrl(string $blockId, ?string $collection = null, ?string $conversion = null): ?string
    {
        $media = $this->getMedia($blockId, $collection);

        return $media?->getFullUrl($conversion);
    }

    protected static function addCropImageConversion(HasMedia&HasMediaAttributes $record, int $width, int $height): Conversion
    {
        return static::addImageConversion($record, self::CONVERSION_CROP, Fit::Crop, $width, $height);
    }

    protected static function addContainImageConversion(HasMedia&HasMediaAttributes $record, int $width, int $height): Conversion
    {
        return static::addImageConversion($record, self::CONVERSION_CONTAIN, Fit::Contain, $width, $height);
    }

    protected static function addImageConversion(HasMedia&HasMediaAttributes $record, string $conversionName, Fit $fitType, int $width, int $height): Conversion
    {
        $conversion = $record->addMediaConversion($conversionName)
            ->withResponsiveImages()
            ->fit($fitType, $width, $height)
            ->format(ImageFormat::WEBP->value);
        FilamentFlexibleBlocksConfig::mergeConfiguredFlexibleBlockImageConversion(static::class, static::getName(), $conversionName, $conversion);
        FilamentFlexibleBlocksConfig::addExtraFlexibleBlockImageConversions(static::class, static::getName(), $record);

        return $conversion;
    }
}
