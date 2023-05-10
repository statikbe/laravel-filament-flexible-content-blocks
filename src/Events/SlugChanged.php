<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event thrown when the slug (or one of its translations) has changed.
 * This can be used to for instance set redirects to old urls.
 */
class SlugChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  array{ locale: string, oldSlug: ?string, newSlug: ?string }  $changedSlugs
     */
    public function __construct(public Model $record, public array $changedSlugs, public bool $recordWasPublished = false)
    {
        //
    }
}
