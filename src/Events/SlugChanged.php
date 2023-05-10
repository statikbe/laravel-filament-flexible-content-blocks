<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
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
     * @param Model $record
     * @param array{ locale: string, oldSlug: ?string, newSlug: ?string } $changedSlugs
     * @param bool $wasPublished
     */
    public function __construct(public Model $record, array $changedSlugs, public bool $wasPublished=false)
    {
        //
    }
}
