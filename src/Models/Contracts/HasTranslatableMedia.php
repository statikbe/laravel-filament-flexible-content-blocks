<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasTranslatedAttributesTrait;

/**
 * To implement translatable images, the translatable media collections need to be tracked.
 *
 * @see HasTranslatedAttributesTrait for the implementation.
 */
interface HasTranslatableMedia
{
    public function mergeTranslatableMediaCollection(array $translatableMediaCollections): void;

    /**
     * @return string[]
     */
    public function getTranslatableMediaCollections(): array;
}
