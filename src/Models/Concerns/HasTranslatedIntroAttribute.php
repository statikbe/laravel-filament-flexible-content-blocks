<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedIntroAttribute
{
    use HasTranslatedAttributes;
    use HasIntroAttribute;

    public function initializeHasTranslatedIntroAttribute(): void
    {
        $this->mergeTranslatable(['intro']);
    }
}
