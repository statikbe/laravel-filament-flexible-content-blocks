<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Field;

class DescriptionField
{
    public static function create(bool $required = false): Field
    {
        $field = static::getFieldName();

        return FlexibleRichEditorField::createTranslatable($field)
            ->label(trans("filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.{$field}_lbl"))
            ->required($required);
    }

    public static function getFieldName(): string
    {
        return 'description';
    }
}
