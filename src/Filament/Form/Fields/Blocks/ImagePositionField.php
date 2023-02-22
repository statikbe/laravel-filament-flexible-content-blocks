<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\Select;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

class ImagePositionField extends Select
{
    const FIELD = 'image_position';

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function create(string $blockClass, bool $required = true): static
    {
        return static::make(self::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.image_position_lbl'))
            ->helperText(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.image_position_help'))
            ->options(FilamentFlexibleBlocksConfig::getImagePositionSelectOptions(self::class))
            ->default(FilamentFlexibleBlocksConfig::getImagePositionDefault(self::class))
            ->disablePlaceholderSelection()
            ->required($required);
    }
}
