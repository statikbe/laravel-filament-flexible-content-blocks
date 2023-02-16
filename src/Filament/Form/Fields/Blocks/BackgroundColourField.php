<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\Select;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

class BackgroundColourField extends Select
{
    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     * @param  bool  $required
     */
    public static function create(string $blockClass, bool $required = false): static
    {
        return static::make('background_colour')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.background_colour_lbl'))
            ->options(FilamentFlexibleBlocksConfig::getBackgroundColoursSelectOptions($blockClass))
            ->default(FilamentFlexibleBlocksConfig::getBackgroundColourDefault($blockClass))
            ->disablePlaceholderSelection()
            ->required($required);
    }
}
