<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\RichEditor;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasTranslatableHint;

class DescriptionField extends RichEditor
{
    use HasTranslatableHint;

    public static function create(bool $required = false): static
    {
        $field = static::getFieldName();

        return static::make($field)
            ->label(trans("filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.{$field}_lbl"))
            ->required($required)
            ->addsTranslatableHint();
    }

    protected static function getFieldName(): string
    {
        return 'description';
    }
}
