<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasIntroAttribute;

/**
 * @mixin HasIntroAttribute
 */
trait HasIntroAttributeTrait
{
    public function initializeHasIntroAttributeTrait(): void
    {
        $this->mergeFillable(['intro']);
    }
}
