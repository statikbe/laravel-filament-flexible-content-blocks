<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

trait HasTranslatedPageAttributesTrait
{
    use HasTranslatedAttributesTrait;
    use HasPageAttributesTrait;

    public function initializeHasTranslatedPageAttributesTrait(): void
    {
        $this->mergeTranslatable(['title']);
    }
}
