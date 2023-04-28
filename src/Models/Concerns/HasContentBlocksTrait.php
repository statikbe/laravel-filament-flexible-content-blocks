<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

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

    public function getSearchableBlockContent(bool $stripHtml=true): string
    {
        $contentBlocksComponent = new ContentBlocks($this);
        $searchableContent = collect($contentBlocksComponent->getSearchableContent());

        if($stripHtml){
            $searchableContent = $searchableContent->map(function($item){
                return strip_tags($item);
            });

            return $searchableContent->implode(" /n ");
        }
        else {
            return $searchableContent->implode(" <br> ");
        }
    }

    protected function registerContentBlocksCollectionsAndConversions()
    {
        foreach (static::registerContentBlocks() as $blockClass) {
            $blockClass::addMediaCollectionAndConversion($this);
        }
    }
}
