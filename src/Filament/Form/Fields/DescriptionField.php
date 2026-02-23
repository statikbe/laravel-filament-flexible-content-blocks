<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Field;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasTranslatableHint;

class DescriptionField
{
    use HasTranslatableHint;

    public static function create(bool $required = false): Field
    {
        $field = static::getFieldName();

        return static::applyTranslatableHint(
            FlexibleRichEditorField::create($field)
                ->label(trans("filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.{$field}_lbl"))
                ->required($required)
        );
    }

    public static function getFieldName(): string
    {
        return 'description';
    }
}
