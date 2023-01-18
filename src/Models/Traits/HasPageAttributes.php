<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

use Carbon\Carbon;

/**
 * @property string|null $title
 * @property Carbon|null $publishing_begins_at
 * @property Carbon|null $publishing_ends_at
 */
trait HasPageAttributes
{
    public function initializeHasPageAttributes(): void
    {
        $this->mergeFillable(['title', 'publishing_begins_at', 'publishing_ends_at']);

        //set casts of attributes:
        $this->mergeCasts([
            'publishing_begins_at' => 'datetime',
            'publishing_ends_at' => 'datetime',
        ]);
    }

    public function isPublished(): bool
    {
        $now = Carbon::now();
        if ($this->publishing_begins_at && $this->publishing_ends_at) {
            return $now->between($this->publishing_begins_at, $this->publishing_ends_at);
        } elseif ($this->publishing_begins_at) {
            return $now->greaterThan($this->publishing_begins_at);
        } elseif ($this->publishing_ends_at) {
            return $now->lessThan($this->publishing_ends_at);
        } else {
            return true;
        }
    }
}
