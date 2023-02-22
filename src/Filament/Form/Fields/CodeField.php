<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class CodeField extends TextInput
{
    const FIELD = 'code';

    public static function create(bool $required = false): static
    {
        return self::make(self::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.code_lbl'))
            ->helperText(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.code_help'))
            ->maxLength(255)
            //disable the field once the code is set, to avoid it breaks the implementation.
            ->disabled(fn (?Model $record) => $record && ! empty($record->code))
            ->required($required);
    }

    protected static function getFieldName(): string
    {
        return 'title';
    }
}
