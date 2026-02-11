<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Enums\ImageFormat;

/**
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property array|null $seo_keywords
 */
trait HasSEOAttributesTrait
{
    use HasMediaAttributesTrait;
    use InteractsWithMedia;

    public function initializeHasSEOAttributesTrait(): void
    {
        $this->mergeFillable(['seo_title', 'seo_description', 'seo_keywords']);
        $this->mergeCasts([
            'seo_keywords' => 'array',
        ]);

        $this->registerSEOImageMediaCollectionAndConversion();
    }

    public function SEOImage(): MorphMany
    {
        return $this->media()->where('collection_name', $this->getSEOImageCollection());
    }

    public function getSEOTitle(): ?string
    {
        if (! $this->seo_title && $this->getTitle()) {
            return $this->getTitle();
        }

        return $this->seo_title;
    }

    public function getSEODescription(): ?string
    {
        if (! $this->seo_description && isset($this->intro)) {
            return Str::squish(strip_tags($this->intro));
        }

        return Str::squish($this->seo_description);
    }

    protected function registerSEOImageMediaCollectionAndConversion()
    {
        $this->addMediaCollection($this->getSEOImageCollection())
            ->registerMediaConversions(function (?Media $media) {
                $conversion = $this->addMediaConversion($this->getSEOImageConversionName())
                    ->fit(Fit::Crop, 1200, 630)
                    ->format(ImageFormat::WEBP->value);
                FilamentFlexibleBlocksConfig::mergeConfiguredModelImageConversion(static::class, $this->getSEOImageCollection(), $this->getSEOImageConversionName(), $conversion);
                FilamentFlexibleBlocksConfig::addExtraModelImageConversions(static::class, $this->getSEOImageCollection(), $this);

                // for filament upload field
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

    public function getSEOImageUrl(?string $conversion = null): ?string
    {
        if ($media = $this->getFallbackImageMedia($this->SEOImage()->first(), $this->getSEOImageCollection())) {
            return $media->getUrl($conversion ?? $this->getSEOImageConversionName());
        } elseif (method_exists($this, 'heroImage')) {
            $heroMedia = $this->getFallbackImageMedia($this->heroImage()->first(), $this->getHeroImageCollection());

            return $heroMedia?->getUrl($conversion ?? $this->getSEOImageConversionName());
        } else {
            return null;
        }
    }
}
