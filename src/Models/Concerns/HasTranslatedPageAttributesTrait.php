<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedPageAttributesTrait
{
    use HasPageAttributesTrait;
    use HasTranslatedAttributesTrait;

    public function initializeHasTranslatedPageAttributesTrait(): void
    {
        $this->mergeTranslatable(['title']);
    }
}
