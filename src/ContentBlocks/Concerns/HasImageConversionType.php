<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns;

use Illuminate\Support\Str;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\ImageConversionTypeField;

trait HasImageConversionType
{
    public ?string $imageConversion;

    protected function setImageConversionType(array $blockData): void
    {
        $this->imageConversion = $blockData[ImageConversionTypeField::FIELD] ?? static::getImageConversionTypeDefault();
    }

    /**
     * @return array<string, string>
     */
    public static function getImageConversionTypeOptions(): array
    {
        return [
            static::CONVERSION_CROP => trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.image_conversion_type_'.Str::lower(static::CONVERSION_CROP)),
            static::CONVERSION_CONTAIN => trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.image_conversion_type_'.Str::lower(static::CONVERSION_CONTAIN)),
        ];
    }

    public static function getImageConversionTypeDefault(): string
    {
        return static::CONVERSION_CROP;
    }

    public function getImageConversionType(?string $conversion = null): ?string
    {
        if ($conversion) {
            return $conversion;
        } elseif ($this->imageConversion) {
            return $this->imageConversion;
        } else {
            return static::getImageConversionTypeDefault();
        }
    }
}
