<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasParent;

/**
 * @mixin HasParent
 * @mixin Model
 */
trait HasParentTrait
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function hasParent(): bool
    {
        return ! is_null($this->parent_id);
    }

    public function isParentOf(self $child): bool
    {
        return $this->id === $child->parent_id;
    }
}
