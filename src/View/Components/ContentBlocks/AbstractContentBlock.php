<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

    use Filament\Forms\Components\Builder\Block;
    use Illuminate\View\Component;

    abstract class AbstractContentBlock extends Component
    {
        abstract public static function getName(): string;

        abstract public static function getFilamentBlock(): Block;
    }
