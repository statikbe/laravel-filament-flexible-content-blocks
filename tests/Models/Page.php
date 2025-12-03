<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Factories\PageFactory;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasAuthorAttributeTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasDefaultContentBlocksTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasHeroCallToActionsTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasHeroImageAttributesTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasIntroAttributeTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasOverviewAttributesTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasPageAttributesTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasSEOAttributesTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasSlugAttributeTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasHeroImageAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasIntroAttribute;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasPageAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasSEOAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable;

class Page extends Model implements HasContentBlocks, HasHeroImageAttributes, HasIntroAttribute, HasMedia, HasMediaAttributes, HasOverviewAttributes, HasPageAttributes, HasSEOAttributes, Linkable
{
    use HasAuthorAttributeTrait;
    use HasDefaultContentBlocksTrait;
    use HasFactory;
    use HasHeroCallToActionsTrait;
    use HasHeroImageAttributesTrait;
    use HasIntroAttributeTrait;
    use HasOverviewAttributesTrait;
    use HasPageAttributesTrait;
    use HasSEOAttributesTrait;
    use HasSlugAttributeTrait;

    protected $guarded = [];

    protected static function newFactory()
    {
        return PageFactory::new();
    }

    public function getViewUrl(?string $locale = null): string
    {
        return config('app.url').'/test-page/'.$this->slug;
    }

    public function getPreviewUrl(?string $locale = null): string
    {
        return $this->getViewUrl($locale);
    }
}
