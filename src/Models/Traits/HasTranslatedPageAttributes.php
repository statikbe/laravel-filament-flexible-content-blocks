<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

use Spatie\Translatable\HasTranslations;

trait HasTranslatedPageAttributes
{
    use HasTranslatedAttributes;
    use HasPageAttributes;

    public function initializeHasTranslatedPageAttributes(): void
    {
        $this->mergeTranslatable(['title']);
    }
}
