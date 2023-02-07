<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes;

class OverviewImageField
{
    public static function create()
    {
        return SpatieMediaLibraryFileUpload::make('overview_image')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.overview_image_lbl'))
            ->collection(function (Model $record) {
                /** @var HasOverviewAttributes $record */
                return $record->getOverviewImageCollection();
            })
            ->conversion('thumbnail');
    }
}
