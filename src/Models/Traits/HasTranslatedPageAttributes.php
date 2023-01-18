<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

trait HasTranslatedPageAttributes
{
    use HasTranslatedAttributes;
    use HasPageAttributes;

    public function initializeHasTranslatedPageAttributes(): void
    {
        $this->mergeTranslatable(['title']);
    }
}
