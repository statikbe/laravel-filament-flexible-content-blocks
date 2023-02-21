<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * A code is a unique field to identify a page
 *
 * @property string $code
 */
interface HasCode
{
    public function scopeCode(Builder $query, string $code): Builder;
}
