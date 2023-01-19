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

    /**
     * Returns whether the page is published or visible, based on the begin and end publishing dates.
     * @return bool
     */
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

    /**
     * Returns whether the page will be published, based on the begin publishing date.
     * @return bool
     */
    public function willBecomePublished(): bool {
        return (isset($this->publishing_begins_at) && $this->publishing_begins_at && $this->publishing_begins_at->isFuture());
    }

    /**
     * Returns whether the page will be unpublished, based on the end publishing date.
     * @return bool
     */
    public function willBecomeUnpublished(): bool {
        return (isset($this->publishing_ends_at) && $this->publishing_ends_at && $this->publishing_ends_at->isFuture());
    }

    /**
     * Returns whether this page was published in the past and its publication ended.
     * @return bool
     */
    public function wasUnpublished(): bool {
        return (isset($this->publishing_ends_at) && $this->publishing_ends_at && $this->publishing_ends_at->isPast());
    }
}
