<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

use App\Models\User;

/**
 * @property User|null $author
 * @property int|null $author_id
 */
trait HasAuthorAttribute
{
    public function initializeHasAuthor(): void
    {
        $this->mergeFillable(['author_id']);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
