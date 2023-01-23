<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

    use Filament\Forms\Components\DateTimePicker;

    class PublishingEndsAtField extends DateTimePicker
    {
        public static function create(): static
        {
            return static::make('publishing_ends_at')
                ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.publishing_ends_at_lbl'))
                ->withoutSeconds()
                ->displayFormat(config('filament-flexible-content-blocks.formatting.publishing_dates', 'd/m/Y G:i'));
        }
    }
