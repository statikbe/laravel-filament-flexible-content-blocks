<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasAuthorAttribute;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasHeroImage;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasIntroAttribute;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasOverviewAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasPageAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasSEOAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasSlugAttribute;

class Page extends Model
{
    use HasFactory;
    use HasPageAttributes;
    use HasIntroAttribute;
    use HasAuthorAttribute;
    use HasHeroImage;
    use HasSEOAttributes;
    use HasOverviewAttributes;
    use HasContentBlocks;
    use HasSlugAttribute;
}
