<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

use Spatie\Translatable\HasTranslations;

trait HasTranslatedSEOAttributes
{
    use HasTranslatedAttributes;
    use HasSEOAttributes;

    public function initializeHasTranslatedSEOAttributes(): void
    {
        $this->mergeTranslatable(['seo_title', 'seo_description']);
    }
}
