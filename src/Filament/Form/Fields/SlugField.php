<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\TextInput;

class SlugField extends TextInput
{
    const FIELD = 'slug';

    public static function create(bool $disabled = true): static
    {
        return self::make(self::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.slug_lbl'))
            ->disabled($disabled)
            ->helperText(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.slug_desc'))
            ->prefix(config('app.url').'/.../');
    }
}
