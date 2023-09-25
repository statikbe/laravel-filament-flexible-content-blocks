<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\Select;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

class BackgroundColourField extends Select
{
    const FIELD = 'background_colour';

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function create(string $blockClass, bool $required = false): static
    {
        return static::make(static::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.background_colour_lbl'))
            ->options(FilamentFlexibleBlocksConfig::getBackgroundColoursSelectOptions($blockClass))
            ->default(FilamentFlexibleBlocksConfig::getBackgroundColourDefault($blockClass))
            ->selectablePlaceholder(false)
            ->required($required);
    }
}
