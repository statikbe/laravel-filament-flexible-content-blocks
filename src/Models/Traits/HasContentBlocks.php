<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

/**
 * @property array $content_blocks
 */
trait HasContentBlocks
{
    public function initializeHasContentBlocks(): void
    {
        //set casts of attributes:
        $this->casts = array_merge(
            parent::getCasts(),
            [
                'content_blocks' => 'array',
            ]
        );
    }

    /**
     * Returns an array of the available content blocks for this model.
     *
     * @return array
     */
    public function getRegisteredContentBlocks(): array
    {
        return config('filament-flexible-content-blocks.default_flexible_blocks', []);
    }
}
