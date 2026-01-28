<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Support\Icons\Heroicon;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasImageEditor;

abstract class ImageField
{
    use HasImageEditor;

    protected static function createImageField(string $field, bool $translatable = false, ?array $externalImageEditorConfig = null): SpatieMediaLibraryFileUpload
    {
        if ($translatable) {
            return self::addImageEditor(
                TranslatableSpatieMediaLibraryFileUpload::make($field)
                    ->hint(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.translatable_image_hint'))
                    ->hintIcon(Heroicon::Language),
                $externalImageEditorConfig
            );
        } else {
            return self::addImageEditor(SpatieMediaLibraryFileUpload::make($field), $externalImageEditorConfig);
        }
    }
}
