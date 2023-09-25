<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\DateTimePicker;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

class PublishingBeginsAtField extends DateTimePicker
{
    const FIELD = 'publishing_begins_at';

    public static function create(): static
    {
        return static::make(static::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.publishing_begins_at_lbl'))
            ->seconds(false)
            ->displayFormat(FilamentFlexibleBlocksConfig::getPublishingDateFormatting());
    }
}
