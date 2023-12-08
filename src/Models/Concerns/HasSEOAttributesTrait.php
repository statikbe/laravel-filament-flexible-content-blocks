<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

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
            ->registerMediaConversions(function (?Media $media) {
                $conversion = $this->addMediaConversion($this->getSEOImageConversionName())
                    ->fit(Manipulations::FIT_CROP, 1200, 630);
                FilamentFlexibleBlocksConfig::mergeConfiguredModelImageConversion(static::class, $this->getSEOImageCollection(), $this->getSEOImageConversionName(), $conversion);
                FilamentFlexibleBlocksConfig::addExtraModelImageConversions(static::class, $this->getSEOImageCollection(), $this);

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
