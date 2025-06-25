<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups;

use Filament\Forms\Components\Section;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasCallToAction;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionRepeater;

class HeroCallToActionSection extends Section
{
    use HasCallToAction;

    public static function create(): static
    {
        return static::make(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.hero_cta_section_title'))
            ->schema([
                CallToActionRepeater::create('hero_call_to_actions', static::class)
                    ->callToActionTypes(static::getCallToActionTypes())
                    ->minItems(0),
            ]);
    }
}
