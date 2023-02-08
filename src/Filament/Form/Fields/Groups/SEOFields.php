<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups;

use Filament\Forms\Components\Grid;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SEODescriptionField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SEOImageField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SEOTitleField;

class SEOFields
{
    public static function create(int $columns = 1, bool $translatableImage = false): Grid
    {
        return Grid::make($columns)
            ->schema([
                SEOTitleField::create(),
                SEODescriptionField::create(),
                SEOImageField::create($translatableImage),
            ]);
    }
}
