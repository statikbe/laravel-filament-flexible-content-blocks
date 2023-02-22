<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

/**
 * @property string|null $overview_title
 * @property string|null $overview_description
 */
trait HasOverviewAttributesTrait
{
    use InteractsWithMedia;
    use HasMediaAttributesTrait;

    public function initializeHasOverviewAttributesTrait(): void
    {
        $this->mergeFillable(['overview_title', 'overview_description']);

        $this->registerOverviewImageMediaCollectionAndConversion();
    }

    public function getOverviewTitle(): ?string
    {
        if (! $this->overview_title && isset($this->title)) {
            return $this->title;
        }
        if (! $this->overview_title && isset($this->seo_title)) {
            return $this->seo_title;
        }

        return $this->overview_title;
    }

    public function getOverviewDescription(): ?string
    {
        if (! $this->overview_description && isset($this->seo_description)) {
            return $this->seo_description;
        }

        return $this->overview_description;
    }

    protected function registerOverviewImageMediaCollectionAndConversion()
    {
        $this->addMediaCollection($this->getOverviewImageCollection())
            ->registerMediaConversions(function (Media $media) {
                $conversion = $this->addMediaConversion($this->getOverviewImageConversionName())
                    ->withResponsiveImages()
                    ->fit(Manipulations::FIT_CROP, 600, 600)
                    ->format(Manipulations::FORMAT_WEBP);
                FilamentFlexibleBlocksConfig::mergeConfiguredModelImageConversion(self::class, $this->getOverviewImageCollection(), $this->getOverviewImageConversionName(), $conversion);

                //for filament upload field
                $this->addFilamentThumbnailMediaConversion();
            });
    }

    public function addOverviewImage(string $imagePath): void
    {
        $this->addMedia($imagePath)
            ->toMediaCollection($this->getOverviewImageCollection());
    }

    public function getOverviewImageConversionName(): string
    {
        return 'overview_image';
    }

    public function getOverviewImageCollection(): string
    {
        return 'overview_image';
    }

    public function getOverviewImageUrl(string $conversion = null): ?string
    {
        return $this->getFirstMediaUrl($this->getOverviewImageCollection(), $conversion ?? $this->getOverviewImageConversionName());
    }

    public function getOverviewImageMedia(array $attributes = []): ?HtmlableMedia
    {
        return $this->getImageHtml(
            $this->getImageMedia($this->getOverviewImageCollection()),
            $this->getOverviewImageConversionName(),
            $this->getOverviewTitle(),
            $attributes);
    }
}
