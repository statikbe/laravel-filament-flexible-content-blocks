<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

/**
 * @property array $content_blocks
 */
trait HasContentBlocksTrait
{
    public function initializeHasContentBlocks(): void
    {
        //set casts of attributes:
        $this->mergeCasts([
            'content_blocks' => 'array',
        ]);
        $this->mergeFillable(['content_blocks']);
    }

    public function getSearchableContent(): string
    {
        return '';
    }
}
