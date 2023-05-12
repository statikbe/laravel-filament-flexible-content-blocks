<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasPageAttributes;

/**
 * @mixin HasPageAttributes
 */
trait HasPageAttributesTrait
{
    public function initializeHasPageAttributesTrait(): void
    {
        $this->mergeFillable(['title', 'publishing_begins_at', 'publishing_ends_at']);

        //set casts of attributes:
        $this->mergeCasts([
            'publishing_begins_at' => 'datetime',
            'publishing_ends_at' => 'datetime',
        ]);
    }

    /**
     * {@inheritDoc}
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

    public function isPublishedForDates(?Carbon $publishingBeginsAt, ?Carbon $publishingEndsAt): bool
    {
        $now = Carbon::now();
        if ($publishingBeginsAt && $publishingEndsAt) {
            return $now->between($publishingBeginsAt, $publishingEndsAt);
        } elseif ($publishingBeginsAt) {
            return $now->greaterThan($publishingBeginsAt);
        } elseif ($publishingEndsAt) {
            return $now->lessThan($publishingEndsAt);
        } else {
            return true;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function willBecomePublished(): bool
    {
        return $this->publishing_begins_at && $this->publishing_begins_at->isFuture();
    }

    /**
     * {@inheritDoc}
     */
    public function willBecomeUnpublished(): bool
    {
        return $this->publishing_ends_at && $this->publishing_ends_at->isFuture();
    }

    /**
     * {@inheritDoc}
     */
    public function wasUnpublished(): bool
    {
        return $this->publishing_ends_at && $this->publishing_ends_at->isPast();
    }

    /**
     * {@inheritDoc}
     */
    public function scopePublished(Builder $query): Builder
    {
        //we need to cover each situation where publishing_begins_at and publishing_ends_at are null:
        return $query->where(function (Builder $publishedQuery) {
            $this->createPublishedSubquery($publishedQuery);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function scopeUnpublished(Builder $query): Builder
    {
        //we need to cover each situation where publishing_begins_at and publishing_ends_at are null:
        return $query->whereNot(function (Builder $publishedQuery) {
            $this->createPublishedSubquery($publishedQuery);
        });
    }

    private function createPublishedSubquery(Builder &$publishedQuery): Builder {
        $publishedQuery->orWhere(function (Builder $option1) {
            $option1->whereNull('publishing_begins_at')
                ->whereNotNull('publishing_ends_at')
                ->where('publishing_ends_at', '>', 'now()');
        })->orWhere(function (Builder $option2) {
            $option2->whereNotNull('publishing_begins_at')
                ->whereNotNull('publishing_ends_at')
                ->whereRaw('now() between `publishing_begins_at` and `publishing_ends_at`');
        })->orWhere(function (Builder $option3) {
            $option3->whereNotNull('publishing_begins_at')
                ->whereNull('publishing_ends_at')
                ->whereRaw('publishing_begins_at < now()');
        })->orWhere(function (Builder $option4) {
            $option4->whereNull('publishing_begins_at')
                ->whereNull('publishing_ends_at');
        });

        return $publishedQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function publishingBeginsAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => optional($this->publishing_begins_at)->format(FilamentFlexibleBlocksConfig::getPublishingDateFormatting()),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function publishingEndsAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => optional($this->publishing_ends_at)->format(FilamentFlexibleBlocksConfig::getPublishingDateFormatting()),
        );
    }
}
