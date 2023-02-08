<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasHeroImageAttributes;

class HeroImageField extends ImageField
{
    public static function create(bool $translatable = false): SpatieMediaLibraryFileUpload
    {
        return static::createImageField('hero_image', $translatable)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.hero_image_lbl'))
            ->collection(function (Model $record) {
                /** @var HasHeroImageAttributes $record */
                return $record->getHeroImageCollection();
            })
            ->conversion('thumbnail');
    }
}