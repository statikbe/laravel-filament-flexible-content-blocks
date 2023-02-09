<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\Select;

class ImageWidthField extends Select
{
    protected static ?array $imageWidthClassMap;

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

    public static function getImageWidthClass(?string $widthType): ?string {
        if(!static::$imageWidthClassMap){
            static::$imageWidthClassMap = collect(config('filament-flexible-content-blocks.image_width.options', []))
                ->mapWithKeys(fn ($item, $key) => [$key => trans($item['class'])]);
        }

        return static::$imageWidthClassMap[$widthType] ?? null;
    }
}
