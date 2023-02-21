<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * A code is a unique field to identify a page
 *
 * @property string $code
 */
trait HasCodeTrait
{
    public function initializeHasCodeTrait(): void
    {
        $this->mergeFillable(['code']);
    }

    public function scopeCode(Builder $query, string $code): Builder
    {
        return $query->where('code', $code);
    }
}
