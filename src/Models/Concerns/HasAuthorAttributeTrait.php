<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->belongsTo(config('filament-flexible-content-blocks.author_model', 'Illuminate\Foundation\Auth\User'), 'author_id');
    }
}
