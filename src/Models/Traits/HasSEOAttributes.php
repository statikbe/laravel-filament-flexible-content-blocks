<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


/**
 * @property string|null $seo_title
 * @property string|null $seo_description
 */
trait HasSEOAttributes
{
    use InteractsWithMedia;

    public function initializeHasSEOAttributes(): void
    {
        $this->registerSEOImageMediaCollectionAndConversion();
    }

    public function getSEOTitle(): string
    {
        if (! $this->seo_title && isset($this->title)) {
            return $this->title;
        }

        return $this->seo_title;
    }

    public function getSEODescription(): string
    {
        return $this->seo_description;
    }

    protected function registerSEOImageMediaCollectionAndConversion()
    {
        $this->addMediaCollection($this->getSEOImageCollection())
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion($this->getSEOImageConversionName())
                    ->fit(Manipulations::FIT_CROP, 1200, 630)
                    ->nonQueued();
            });
    }

    public function addSEOImage(string $imagePath): void
    {
        $this->addMedia($imagePath)
            ->toMediaCollection($this->getSEOImageCollection());
    }

    public function getSEOImageConversionName(): string
    {
        return 'seo_image';
    }

    public function getSEOImageCollection(): string
    {
        return 'seo_image';
    }

    public function getSEOImageUrl(string $conversion = null): string
    {
        return $this->getFirstMediaUrl($this->getSEOImageCollection(), $conversion ?? $this->getSEOImageConversionName());
    }
}
