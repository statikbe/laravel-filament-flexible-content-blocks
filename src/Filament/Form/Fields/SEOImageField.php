<?php

    namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

    use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

    class SEOImageField extends SpatieMediaLibraryFileUpload {
        public static function create() {
            return SpatieMediaLibraryFileUpload::make('seo_image')
                ->;
        }
    }
