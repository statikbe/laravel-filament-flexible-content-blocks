<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups;

    use Filament\Forms\Components\Section;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\HeroImageCopyrightField;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\HeroImageField;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\HeroImageTitleField;

    class HeroImageSection extends Section
    {
        public static function create(): static
        {
            return static::make(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.hero_image_section_title'))
                ->schema([
                    HeroImageTitleField::create(),
                    HeroImageCopyrightField::create(),
                    HeroImageField::create(),
                ]);
        }
    }
