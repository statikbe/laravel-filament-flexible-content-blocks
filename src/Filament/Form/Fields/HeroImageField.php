<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

    use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
    use Illuminate\Database\Eloquent\Model;

    class HeroImageField extends SpatieMediaLibraryFileUpload
    {
        public static function create()
        {
            return SpatieMediaLibraryFileUpload::make('hero_image')
                ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.hero_image_lbl'))
                ->collection(function (Model $record) {
                    return $record->getHeroImageCollection();
                })
                ->conversion('thumbnail');
        }
    }
