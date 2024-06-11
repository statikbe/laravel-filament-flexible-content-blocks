<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Textarea;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasTranslatableHint;

use function trans;

class SEODescriptionField extends Textarea
{
    use HasTranslatableHint;

    public static function create(bool $required = false): static
    {
        $field = static::getFieldName();

        return static::make($field)
            ->label(
                trans("filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.{$field}_lbl")
            )
            ->rows(3)
            ->required($required)
            ->addsTranslatableHint();
    }

    public static function getFieldName(): string
    {
        return 'seo_description';
    }
}
