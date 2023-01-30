<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

use App\Models\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Authenticatable|null $author
 * @property int|null $author_id
 */
trait HasAuthorAttribute
{
    public function initializeHasAuthor(): void
    {
        $this->mergeFillable(['author_id']);
    }

    public function author(): BelongsTo
    {
        //TODO replace User::class with something more generic in line with laravel configuration in auth.php
        return $this->belongsTo(User::class, 'author_id');
    }
}
