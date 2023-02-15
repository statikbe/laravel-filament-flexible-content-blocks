<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\Select;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;

class ImageWidthField extends Select
{
    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function create(string $blockClass, bool $required = true): static
    {
        $widths = self::getImageWidthConfig($blockClass);
        $widthOptions = collect($widths['options'] ?? [])
            ->mapWithKeys(fn ($item, $key) => [$key => trans($item['label'])]);

        $defaultWidth = $widths['default'] ?? null;

        return static::make('image_width')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.image_width_lbl'))
            ->options($widthOptions)
            ->default($defaultWidth)
            ->disablePlaceholderSelection()
            ->required($required);
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function getImageWidthClass(string $blockClass, ?string $widthType): ?string
    {
        $widths = self::getImageWidthConfig($blockClass);
        $imageWidthClassMap = collect($widths['options'] ?? [])
            ->mapWithKeys(fn ($item, $key) => [$key => trans($item['class'])]);

        return $imageWidthClassMap[$widthType] ?? null;
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    private static function getImageWidthConfig(string $blockClass): array
    {
        return config("filament-flexible-content-blocks.block_specific.$blockClass.image_width",
            config('filament-flexible-content-blocks.image_width', [])
        );
    }
}
