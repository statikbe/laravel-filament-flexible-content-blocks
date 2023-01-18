<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

trait HasTranslatedSEOAttributes
{
    use HasTranslatedAttributes;
    use HasSEOAttributes;

    public function initializeHasTranslatedSEOAttributes(): void
    {
        $this->mergeTranslatable(['seo_title', 'seo_description']);
    }
}
