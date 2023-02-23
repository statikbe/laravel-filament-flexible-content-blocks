<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes;

class OverviewImageField extends ImageField
{
    const FIELD = 'overview_image';

    public static function create(bool $translatable = false): SpatieMediaLibraryFileUpload
    {
        return static::createImageField(static::FIELD, $translatable)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.overview_image_lbl'))
            ->collection(function (Model $record) {
                /** @var HasOverviewAttributes $record */
                return $record->getOverviewImageCollection();
            })
            ->conversion('thumbnail');
    }
}
