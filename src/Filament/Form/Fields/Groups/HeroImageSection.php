<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\HeroImageCopyrightField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\HeroImageField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\HeroImageTitleField;

class HeroImageSection extends Section
{
    public static function create(bool $translatableImage = false): static
    {
        return static::make(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.hero_image_section_title'))
            ->schema([
                Grid::make(2)->schema([
                    HeroImageField::create($translatableImage),

                    Grid::make(1)->schema([
                        HeroImageTitleField::create(),
                        HeroImageCopyrightField::create(),
                    ])->columnSpan(1),
                ]),
            ]);
    }
}
