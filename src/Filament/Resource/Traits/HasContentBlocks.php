<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Resource\Traits;

    trait HasContentBlocks
    {
        public static function getFilamentContentBlocks(): array
        {
            $filamentBlocks = [];
            foreach (static::getContentBlockClasses() as $blockClass) {
                $filamentBlocks[$blockClass::getName()] = $blockClass::make();
            }

            return $filamentBlocks;
        }
    }
