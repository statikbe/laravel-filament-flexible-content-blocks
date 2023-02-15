<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns;

    use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

    trait HasBackgroundColour
    {
        public ?string $backgroundColourType;

        public function getBackgroundColourClass(): ?string
        {
            return FilamentFlexibleBlocksConfig::getBackgroundColourClass(self::class, $this->backgroundColourType);
        }
    }
