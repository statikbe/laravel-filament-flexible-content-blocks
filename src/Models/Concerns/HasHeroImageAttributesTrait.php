<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasHeroImageAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Enums\ImageFormat;

/**
 * @mixin HasHeroImageAttributes
 */
trait HasHeroImageAttributesTrait
{
    use HasMediaAttributesTrait;
    use InteractsWithMedia;

    public function initializeHasHeroImageAttributesTrait(): void
    {
        $this->registerHeroImageMediaCollectionAndConversion();
        $this->mergeFillable(['hero_image_title', 'hero_image_copyright']);
    }

    public function heroImage(): MorphMany
    {
        return $this->media()->where('collection_name', $this->getHeroImageCollection());
    }

    protected function registerHeroImageMediaCollectionAndConversion()
    {
        $this->addMediaCollection($this->getHeroImageCollection())
            ->registerMediaConversions(function (?Media $media) {
                $conversion = $this->addMediaConversion($this->getHeroImageConversionName())
                    ->withResponsiveImages()
                    ->fit(Fit::Crop, 1200, 630)
                    ->format(ImageFormat::WEBP->value);
                FilamentFlexibleBlocksConfig::mergeConfiguredModelImageConversion(static::class, $this->getHeroImageCollection(), $this->getHeroImageConversionName(), $conversion);
                FilamentFlexibleBlocksConfig::addExtraModelImageConversions(static::class, $this->getHeroImageCollection(), $this);

                // for filament upload field
                $this->addFilamentThumbnailMediaConversion();

                // add extra conversion for overview image format, when the hero is used as fallback for the overview image:
                if (method_exists($this, 'overviewImage')) {
                    $overviewConversion = $this->addMediaConversion($this->getOverviewImageConversionName())
                        ->withResponsiveImages()
                        ->fit(Fit::Crop, 600, 600)
                        ->format(ImageFormat::WEBP->value);
                    FilamentFlexibleBlocksConfig::mergeConfiguredModelImageConversion(static::class, $this->getHeroImageCollection(), $this->getOverviewImageConversionName(), $overviewConversion);
                }

                // add extra conversion for SEO image format, when the hero is used as fallback for the SEO image:
                if (method_exists($this, 'heroImage')) {
                    $seoConversion = $this->addMediaConversion($this->getSEOImageConversionName())
                        ->fit(Fit::Crop, 1200, 630)
                        ->format(ImageFormat::WEBP->value);
                    FilamentFlexibleBlocksConfig::mergeConfiguredModelImageConversion(static::class, $this->getHeroImageCollection(), $this->getSEOImageConversionName(), $seoConversion);
                }
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

    public function getHeroImageUrl(?string $conversion = null): ?string
    {
        $media = $this->getFallbackImageMedia($this->heroImage->first(), $this->getHeroImageCollection());

        return $media?->getUrl($conversion ?? $this->getHeroImageConversionName());
    }

    public function getHeroImageMedia(?string $conversion = null, array $attributes = []): ?HtmlableMedia
    {
        return $this->getImageHtml(
            $this->getFallbackImageMedia($this->heroImage()->first(), $this->getHeroImageCollection()),
            $conversion ?? $this->getHeroImageConversionName(),
            $this->hero_image_title,
            $attributes);
    }

    public function hasHeroImage(): bool
    {
        return $this->hasMedia($this->getHeroImageCollection());
    }
}
