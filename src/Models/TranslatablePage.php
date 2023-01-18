<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models;

use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasTranslatedContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasTranslatedOverviewAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasTranslatedPageAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasTranslatedSEOAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasTranslatedSlugAttribute;

class TranslatablePage extends Model
{
    use HasTranslatedPageAttributes;
    use HasTranslatedSlugAttribute;
    use HasTranslatedSEOAttributes;
    use HasTranslatedContentBlocks;
    use HasTranslatedOverviewAttributes;
}
