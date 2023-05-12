<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string|null $title
 * @property Carbon|null $publishing_begins_at
 * @property Carbon|null $publishing_ends_at
 */
interface HasPageAttributes
{
    /**
     * Returns whether the page is published or visible, based on the begin and end publishing dates.
     */
    public function isPublished(): bool;

    /**
     * Returns whether for the given timestamps the page is published or visible.
     */
    public function isPublishedForDates(?Carbon $publishingBeginsAt, ?Carbon $publishingEndsAt): bool;

    /**
     * Returns whether the page will be published, based on the begin publishing date.
     */
    public function willBecomePublished(): bool;

    /**
     * Returns whether the page will be unpublished, based on the end publishing date.
     */
    public function willBecomeUnpublished(): bool;

    /**
     * Returns whether this page was published in the past and its publication ended.
     */
    public function wasUnpublished(): bool;

    /**
     * Find the published pages based on the publishing dates.
     */
    public function scopePublished(Builder $query): Builder;

    /**
     * Find the unpublished pages based on the publishing dates.
     */
    public function scopeUnpublished(Builder $query): Builder;

    /**
     * Returns the formatted timestamp of the beginning of the publication.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
     */
    public function publishingBeginsAtFormatted(): Attribute;

    /**
     * Returns the formatted timestamp of the end of the publication.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
     */
    public function publishingEndsAtFormatted(): Attribute;
}
