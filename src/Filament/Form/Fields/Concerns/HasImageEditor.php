<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

trait HasImageEditor
{
    public static function addImageEditor(SpatieMediaLibraryFileUpload $imageField, ?array $externalImageEditorConfig = null): SpatieMediaLibraryFileUpload
    {
        return FilamentFlexibleBlocksConfig::getImageEditorConfig($imageField, $externalImageEditorConfig);
    }
}
