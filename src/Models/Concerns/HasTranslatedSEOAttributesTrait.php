<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedSEOAttributesTrait
{
    use HasTranslatedAttributesTrait;
    use HasSEOAttributesTrait;

    public function initializeHasTranslatedSEOAttributesTrait(): void
    {
        $this->mergeTranslatable(['seo_title', 'seo_description']);
    }
}
