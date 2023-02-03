<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

/**
 * @property string|null $intro
 */
trait HasIntroAttribute
{
    public function initializeHasIntroAttribute(): void
    {
        $this->mergeFillable(['intro']);
    }
}
