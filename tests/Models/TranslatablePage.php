<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasAuthorAttributeTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasCodeTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasDefaultContentBlocksTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasTranslatedContentBlocksTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasTranslatedHeroCallToActionsTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasTranslatedHeroImageAttributesTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasTranslatedIntroAttributeTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasTranslatedOverviewAttributesTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasTranslatedPageAttributesTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasTranslatedSEOAttributesTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasTranslatedSlugAttributeTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasCode;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasHeroImageAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasIntroAttribute;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasPageAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasSEOAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Factories\TranslatablePageFactory;

class TranslatablePage extends Model implements HasCode, HasContentBlocks, HasHeroImageAttributes, HasIntroAttribute, HasMedia, HasMediaAttributes, HasOverviewAttributes, HasPageAttributes, HasSEOAttributes, Linkable
{
    use HasAuthorAttributeTrait;
    use HasCodeTrait;
    use HasDefaultContentBlocksTrait;
    use HasFactory;
    use HasTranslatedContentBlocksTrait;
    use HasTranslatedHeroCallToActionsTrait;
    use HasTranslatedHeroImageAttributesTrait;
    use HasTranslatedIntroAttributeTrait;
    use HasTranslatedOverviewAttributesTrait;
    use HasTranslatedPageAttributesTrait;
    use HasTranslatedSEOAttributesTrait;
    use HasTranslatedSlugAttributeTrait;
    use InteractsWithMedia;

    protected $guarded = [];

    /**
     * Override to disable media conversions in tests to avoid image processing errors
     * with fake uploaded files
     */
    public function registerMediaConversions(?\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        // Don't register any conversions in tests
    }

    protected static function newFactory()
    {
        return TranslatablePageFactory::new();
    }

    public function getViewUrl(?string $locale = null): string
    {
        $slug = $this->getTranslation('slug', $locale ?? app()->getLocale());

        return config('app.url').'/test-page/'.$slug;
    }

    public function getPreviewUrl(?string $locale = null): string
    {
        return $this->getViewUrl($locale);
    }
}
