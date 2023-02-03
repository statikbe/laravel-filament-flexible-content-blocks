<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

/**
 * @property array $content_blocks
 */
interface HasContentBlocks
{
    /**
     * Extract all searchable text from the content blocks, to enable simple searching in content blocks.
     *
     * @return string|null
     */
    public function getSearchableContent(): ?string;
}
