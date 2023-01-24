<?php

    namespace Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

    use Illuminate\View\Component;
    use Filament\Forms\Components\Builder\Block;

    abstract class AbstractContentBlock extends Component {
        abstract public static function getName(): string;

        abstract public static function getFilamentBlock(): Block;
    }
