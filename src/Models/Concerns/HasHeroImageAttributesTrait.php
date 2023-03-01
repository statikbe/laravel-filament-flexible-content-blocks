<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasHeroImageAttributes;

/**
 * @mixin HasHeroImageAttributes
 */
trait HasHeroImageAttributesTrait
{
    use InteractsWithMedia;
    use HasMediaAttributesTrait;

    public function initializeHasHeroImageAttributesTrait(): void
    {
        $this->registerHeroImageMediaCollectionAndConversion();
        $this->mergeFillable(['hero_image_title', 'hero_image_copyright']);
    }

    protected function registerHeroImageMediaCollectionAndConversion()
    {
        $this->addMediaCollection($this->getHeroImageCollection())
            ->registerMediaConversions(function (Media $media) {
                $conversion = $this->addMediaConversion($this->getHeroImageConversionName())
                    ->withResponsiveImages()
                    ->fit(Manipulations::FIT_CROP, 1200, 630)
                    ->format(Manipulations::FORMAT_WEBP);
                FilamentFlexibleBlocksConfig::mergeConfiguredModelImageConversion(static::class, $this->getHeroImageCollection(), $this->getHeroImageConversionName(), $conversion);

                //for filament upload field
                $this->addFilamentThumbnailMediaConversion();
            });
    }

    public function addHeroImage(string $imagePath): void
    {
        $this->addMedia($imagePath)
            ->toMediaCollection($this->getHeroImageCollection());
    }

    public function getHeroImageConversionName(): string
    {
        return 'hero_image';
    }

    public function getHeroImageCollection(): string
    {
        return 'hero_image';
    }

    public function getHeroImageUrl(string $conversion = null): ?string
    {
        return $this->getFirstMediaUrl($this->getHeroImageCollection(), $conversion ?? $this->getHeroImageConversionName());
    }

    public function getHeroImageMedia(array $attributes = []): ?HtmlableMedia
    {
        return $this->getImageHtml(
            $this->getImageMedia($this->getHeroImageCollection()),
            $this->getHeroImageConversionName(),
            $this->hero_image_title,
            $attributes);
    }

    public function hasHeroImage(): bool
    {
        return $this->hasMedia($this->getHeroImageCollection());
    }
}
