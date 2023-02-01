<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups;

use Filament\Forms\Components\Section;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\PublishingBeginsAtField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\PublishingEndsAtField;

class PublicationSection extends Section
{
    public static function create(): static {
        return static::make(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.publication_section_title'))
            ->schema([
                PublishingBeginsAtField::create(),
                PublishingEndsAtField::create(),
            ])
            ->columns(2);
    }
}
