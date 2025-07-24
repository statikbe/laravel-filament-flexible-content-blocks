<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups;

use Filament\Forms\Components\Section;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\HeroCallToActionsField;

class HeroCallToActionSection extends Section
{
    public static function create(): static
    {
        return static::make(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.hero_cta_section_title'))
            ->schema([
                HeroCallToActionsField::createField(),
            ]);
    }
}
