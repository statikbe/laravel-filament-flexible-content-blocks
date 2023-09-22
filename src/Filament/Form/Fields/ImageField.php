<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

abstract class ImageField
{
    protected static function createImageField(string $field, bool $translatable = false): SpatieMediaLibraryFileUpload
    {
        if ($translatable) {
            return TranslatableSpatieMediaLibraryFileUpload::make($field)
                ->hint(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.translatable_image_hint'))
                ->hintIcon('heroicon-s-language');
        } else {
            return SpatieMediaLibraryFileUpload::make($field);
        }
    }
}
