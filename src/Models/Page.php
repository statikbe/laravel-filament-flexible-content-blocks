<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasAuthorAttributeTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasDefaultContentBlocksTrait;
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

class Page extends Model implements HasMedia, HasMediaAttributes, HasPageAttributes, HasHeroImageAttributes, HasContentBlocks, HasIntroAttribute, HasSEOAttributes, HasOverviewAttributes, Linkable
{
    use HasFactory;
    use HasPageAttributesTrait;
    use HasIntroAttributeTrait;
    use HasAuthorAttributeTrait;
    use HasHeroImageAttributesTrait;
    use HasSEOAttributesTrait;
    use HasOverviewAttributesTrait;
    use HasDefaultContentBlocksTrait;
    use HasSlugAttributeTrait;

    public function getViewUrl(): string
    {
        //todo implement controller and add route:
        return config('app.url');
    }
}
