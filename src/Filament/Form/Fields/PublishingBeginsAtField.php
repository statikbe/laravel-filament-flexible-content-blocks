<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\DateTimePicker;

class PublishingBeginsAtField extends DateTimePicker
{
    const FIELD = 'publishing_begins_at';

    public static function create(): static
    {
        return static::make(self::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.publishing_begins_at_lbl'))
            ->withoutSeconds()
            ->displayFormat(config('filament-flexible-content-blocks.formatting.publishing_dates', 'd/m/Y G:i'));
    }
}
