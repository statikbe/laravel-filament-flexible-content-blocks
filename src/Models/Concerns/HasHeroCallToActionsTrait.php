<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property array $hero_call_to_actions
 */
trait HasHeroCallToActionsTrait
{
    public function initializeHasHeroCallToActionsTrait(): void
    {
        $this->mergeCasts([
            'hero_call_to_actions' => 'array',
        ]);
        $this->mergeFillable(['hero_call_to_actions']);
    }
}
