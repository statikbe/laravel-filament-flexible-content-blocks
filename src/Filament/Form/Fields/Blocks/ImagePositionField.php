<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\Select;

class ImagePositionField extends Select
{
    public static function create(bool $required = true): static
    {
        $positionOptions = collect(config('filament-flexible-content-blocks.image_position.options', []))
            ->mapWithKeys(fn ($item, $key) => [$key => trans($item)]);

        return static::make('image_position')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.image_position_lbl'))
            ->helperText(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.image_position_help'))
            ->options($positionOptions)
            ->default(config('filament-flexible-content-blocks.image_position.default', 'left'))
            ->disablePlaceholderSelection()
            ->required($required);
    }
}
