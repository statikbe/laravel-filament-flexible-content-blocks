<?php

    namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

    use Filament\Forms\Components\TextInput;

    class TitleField extends TextInput {
        public static function create(bool $required=false): static {
            $field = static::getFieldName();
            return self::make($field)
                ->label(trans("filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.{$field}_lbl"))
                ->length(255)
                ->required($required);
        }

        protected static function getFieldName(): string {
            return 'title';
        }
    }
