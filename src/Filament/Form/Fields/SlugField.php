<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class SlugField extends TextInput
{
    const FIELD = 'slug';

    public static function create(bool $disabled = true): static
    {
        return self::make(self::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.slug_lbl'))
            ->disabled($disabled)
            ->helperText(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.slug_desc'))
            ->prefix(config('app.url').'/.../')
            //make the slug required on edit. on create, the slug is not required so if kept blank a slug is generated.
            ->required(fn(?Model $record): bool => $record && $record->id > 0);
    }
}
