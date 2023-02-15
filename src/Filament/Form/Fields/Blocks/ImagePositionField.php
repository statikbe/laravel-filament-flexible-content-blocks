<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\Select;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;

class ImagePositionField extends Select
{
    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function create(string $blockClass, bool $required = true): static
    {
        $positions = config("filament-flexible-content-blocks.block_specific.$blockClass.image_position.options",
            config('filament-flexible-content-blocks.image_position.options', [])
        );
        $positionOptions = collect($positions)
            ->mapWithKeys(fn ($item, $key) => [$key => trans($item)]);

        $defaultPosition = config("filament-flexible-content-blocks.block_specific.$blockClass.image_position.default",
            config('filament-flexible-content-blocks.image_position.default', 'left'));

        return static::make('image_position')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.image_position_lbl'))
            ->helperText(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.image_position_help'))
            ->options($positionOptions)
            ->default($defaultPosition)
            ->disablePlaceholderSelection()
            ->required($required);
    }
}
