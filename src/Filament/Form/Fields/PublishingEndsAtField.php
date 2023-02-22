<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\DateTimePicker;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

class PublishingEndsAtField extends DateTimePicker
{
    const FIELD = 'publishing_ends_at';

    public static function create(): static
    {
        return static::make('publishing_ends_at')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.publishing_ends_at_lbl'))
            ->withoutSeconds()
            ->displayFormat(FilamentFlexibleBlocksConfig::getPublishingDateFormatting());
    }
}
