<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

abstract class ImageField
{
    protected static function createImageField(string $field, bool $translatable = false): SpatieMediaLibraryFileUpload
    {
        if ($translatable) {
            $fileUploadClass = TranslatableSpatieMediaLibraryFileUpload::class;
        } else {
            $fileUploadClass = SpatieMediaLibraryFileUpload::class;
        }

        return $fileUploadClass::make($field);
    }
}
