<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

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
