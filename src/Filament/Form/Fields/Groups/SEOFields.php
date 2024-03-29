<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Grid;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Actions\SEOAIAction;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SEODescriptionField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SEOImageField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SEOKeywordsField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SEOTitleField;

class SEOFields
{
    public static function create(int $columns = 1, bool $translatableImage = false): Grid
    {
        return Grid::make($columns)
            ->schema([
                Actions::make([
                    SEOAIAction::create(),
                ]),
                SEOTitleField::create(),
                SEODescriptionField::create(),
                SEOKeywordsField::create(),
                SEOImageField::create($translatableImage),
            ]);
    }
}
