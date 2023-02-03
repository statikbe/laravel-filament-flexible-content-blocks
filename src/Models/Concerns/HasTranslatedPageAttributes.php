<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedPageAttributes
{
    use HasTranslatedAttributes;
    use HasPageAttributes;

    public function initializeHasTranslatedPageAttributes(): void
    {
        $this->mergeTranslatable(['title']);
    }
}
