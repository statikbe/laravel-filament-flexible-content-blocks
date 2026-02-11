<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups;

use Filament\Schemas\Components\Grid;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\OverviewDescriptionField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\OverviewImageField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\OverviewTitleField;

class OverviewFields
{
    public static function create(int $columns = 1, bool $translatableImage = false): Grid
    {
        return Grid::make($columns)
            ->columnSpanFull()
            ->schema([
                OverviewTitleField::create(),
                OverviewDescriptionField::create(),
                OverviewImageField::create($translatableImage),
            ]);
    }
}
