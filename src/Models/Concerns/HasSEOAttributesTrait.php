<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property string|null $seo_title
 * @property string|null $seo_description
 */
trait HasSEOAttributesTrait
{
    use InteractsWithMedia;
    use HasMediaAttributesTrait;

    public function initializeHasSEOAttributesTrait(): void
    {
        $this->mergeFillable(['seo_title', 'seo_description']);

        $this->registerSEOImageMediaCollectionAndConversion();
    }

    public function getSEOTitle(): ?string
    {
        if (! $this->seo_title && isset($this->title)) {
            return $this->title;
        }

        return $this->seo_title;
    }

    public function getSEODescription(): ?string
    {
        if (! $this->seo_description && isset($this->intro)) {
            return $this->intro;
        }

        return $this->seo_description;
    }

    protected function registerSEOImageMediaCollectionAndConversion()
    {
        $this->addMediaCollection($this->getSEOImageCollection())
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion($this->getSEOImageConversionName())
                    ->fit(Manipulations::FIT_CROP, 1200, 630);
                //for filament upload field
                $this->addFilamentThumbnailMediaConversion();
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

    public function getSEOImageUrl(string $conversion = null): ?string
    {
        $media = $this->getImageMedia($this->getSEOImageCollection());
        return $media?->getUrl($conversion ?? $this->getSEOImageConversionName());
    }
}
