<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property self $parent
 * @property $parent_id
 * @property Collection $children
 */
interface HasParent
{
    /**
     * The recursive parent relationship to model parent - child content.
     */
    public function parent(): BelongsTo;

    /**
     * The children relationship models the subcontent of parent content.
     */
    public function children(): HasMany;

    /**
     * Returns true if this model has a parent.
     */
    public function hasParent(): bool;

    /**
     * Checks if this model is the parent of the given $child.
     */
    public function isParentOf(self $child): bool;
}
