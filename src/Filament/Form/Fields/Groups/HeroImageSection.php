<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\HeroImageCopyrightField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\HeroImageField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\HeroImageTitleField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\HeroVideoUrlField;

class HeroImageSection extends Section
{
    public static function create(bool $translatableImage = false, bool $enableVideoUrlField = false): static
    {
        $fields = [
            HeroImageField::create($translatableImage),
            Grid::make(1)->schema([
                HeroImageTitleField::create(),
                HeroImageCopyrightField::create(),
            ])->columnSpan(1),
        ];

        if ($enableVideoUrlField) {
            $fields[] = HeroVideoUrlField::create();
        }

        return static::make(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.hero_image_section_title'))
            ->columnSpanFull()
            ->schema([
                Grid::make(2)->columnSpanFull()->schema($fields),
            ]);
    }
}
