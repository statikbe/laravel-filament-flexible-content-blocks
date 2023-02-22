<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

/**
 * @property Model $author
 * @property int|null $author_id
 */
trait HasAuthorAttributeTrait
{
    public function initializeHasAuthorTrait(): void
    {
        $this->mergeFillable(['author_id']);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(FilamentFlexibleBlocksConfig::getAuthorModel(), 'author_id');
    }
}
