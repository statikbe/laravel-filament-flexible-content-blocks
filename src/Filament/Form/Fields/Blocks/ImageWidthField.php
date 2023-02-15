<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\Select;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

class ImageWidthField extends Select
{
    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     * @param  bool  $required
     * @return static
     */
    public static function create(string $blockClass, bool $required = true): static
    {
        return static::make('image_width')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.image_width_lbl'))
            ->options(FilamentFlexibleBlocksConfig::getImageWidthSelectOptions($blockClass))
            ->default(FilamentFlexibleBlocksConfig::getImageWidthDefault($blockClass))
            ->disablePlaceholderSelection()
            ->required($required);
    }
}
