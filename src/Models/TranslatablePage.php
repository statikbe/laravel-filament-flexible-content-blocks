<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasAuthorAttribute;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasHeroImage;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasTranslatedContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasTranslatedOverviewAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasTranslatedPageAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasTranslatedSEOAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasTranslatedSlugAttribute;

class TranslatablePage extends Model
{
    use HasFactory;
    use HasTranslatedPageAttributes;
    use HasAuthorAttribute;
    use HasHeroImage;
    use HasTranslatedSlugAttribute;
    use HasTranslatedSEOAttributes;
    use HasTranslatedContentBlocks;
    use HasTranslatedOverviewAttributes;
}
