<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

/**
 * @mixin HasContentBlocks
 *
 * @property array $content_blocks
 */
trait HasContentBlocksTrait
{
    //cache of instantiated blocks:
    protected static array $filamentContentBlocks = [];

    public function initializeHasContentBlocksTrait(): void
    {
        //set casts of attributes:
        $this->mergeCasts([
            'content_blocks' => 'array',
        ]);
        $this->mergeFillable(['content_blocks']);

        $this->registerContentBlocksCollectionsAndConversions();
    }

    public static function getFilamentContentBlocks(): array
    {
        if (empty(static::$filamentContentBlocks)) {
            $filamentBlocks = [];
            foreach (static::registerContentBlocks() as $blockClass) {
                $filamentBlocks[$blockClass::getName()] = $blockClass::make();
            }
            static::$filamentContentBlocks = $filamentBlocks;
        }

        return static::$filamentContentBlocks;
    }

    public function getSearchableContent(): string
    {
        return '';
    }

    protected function registerContentBlocksCollectionsAndConversions()
    {
        foreach (static::registerContentBlocks() as $blockClass) {
            $blockClass::addMediaCollectionAndConversion($this);
        }
    }

    public function getLinkableModels(): array
    {
        $classes = get_declared_classes();
        $implementsLinkable = [];
        foreach ($classes as $klass) {
            $reflect = new ReflectionClass($klass);
            if ($reflect->implementsInterface('IModule')) {
                $implementsLinkable[] = $klass;
            }
        }

        return $implementsLinkable;
    }
}
