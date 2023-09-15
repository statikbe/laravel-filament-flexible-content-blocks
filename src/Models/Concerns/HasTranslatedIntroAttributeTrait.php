<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedIntroAttributeTrait
{
    use HasIntroAttributeTrait;
    use HasTranslatedAttributesTrait;

    public function initializeHasTranslatedIntroAttributeTrait(): void
    {
        $this->mergeTranslatable(['intro']);
    }
}
