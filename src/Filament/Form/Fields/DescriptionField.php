<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

    use Filament\Forms\Components\RichEditor;

    class DescriptionField extends RichEditor
    {
        public static function create(bool $required = false): static
        {
            $field = static::getFieldName();

            return self::make($field)
                ->label(trans("filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.{$field}_lbl"))
                ->required($required);
        }

        protected static function getFieldName(): string
        {
            return 'description';
        }
    }
