<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\Select;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

class GridColumnsField extends Select
{
    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function create(string $blockClass, bool $required = true): self
    {
        return self::make('grid_columns')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.grid_columns_lbl'))
            ->options(FilamentFlexibleBlocksConfig::getGridColumnsSelectOptions($blockClass))
            ->required($required);
    }
}
