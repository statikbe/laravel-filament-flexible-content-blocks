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
            ->disabled(fn (Model $record) => ! empty($record->code))
            ->required($required);
    }

    protected static function getFieldName(): string
    {
        return 'title';
    }
}
