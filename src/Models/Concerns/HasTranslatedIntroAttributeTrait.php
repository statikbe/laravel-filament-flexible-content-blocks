<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedIntroAttributeTrait
{
    use HasTranslatedAttributesTrait;
    use HasIntroAttributeTrait;

    public function initializeHasTranslatedIntroAttributeTrait(): void
    {
        $this->mergeTranslatable(['intro']);
    }
}
