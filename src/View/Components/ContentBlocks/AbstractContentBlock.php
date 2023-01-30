<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

    use Filament\Forms\Components\Builder\Block;
    use Illuminate\View\Component;

    abstract class AbstractContentBlock extends Component
    {
        abstract public static function getName(): string;

        public static function getFilamentBlock(): Block {
            return Block::make(static::getName());
        }
    }
