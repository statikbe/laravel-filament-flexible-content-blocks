<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

    use Filament\Forms\Components\Select;

    class ImageWidthField extends Select
    {
        public static function create(bool $required = true): static
        {
            $widthOptions = collect(config('filament-flexible-content-blocks.image_width.options', []))
                ->mapWithKeys(fn ($item, $key) => [$key => trans($item['label'])]);

            return static::make('image_width')
                ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.image_width_lbl'))
                ->options($widthOptions)
                ->default(config('filament-flexible-content-blocks.image_width.default', 'full'))
                ->disablePlaceholderSelection()
                ->required($required);
        }
    }
