<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\Select;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

class BlockStyleField extends Select
{
    const FIELD = 'block_style';

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function create(string $blockClass, bool $required = true): static
    {
        return static::make(static::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.block_style_lbl'))
            ->helperText(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.block_style_help'))
            ->options(FilamentFlexibleBlocksConfig::getBlockStyleSelectOptions($blockClass))
            ->default(FilamentFlexibleBlocksConfig::getBlockStyleDefault($blockClass))
            //only show when enabled and when there are styles configured:
            ->visible($blockClass::hasBlockStyles() && count(FilamentFlexibleBlocksConfig::getBlockStyleSelectOptions($blockClass)) > 1)
            ->selectablePlaceholder(false)
            ->required($required);
    }
}
