<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Select;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasPageAttributes;

class ParentField extends Select
{
    const FIELD = 'parent';

    public static function create(): static
    {
        return static::make(static::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.parent_lbl'))
            ->helperText(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.parent_help'))
            ->relationship(name: 'parent', titleAttribute: null)
            ->getOptionLabelFromRecordUsing(fn (HasPageAttributes $record) => $record->title)
            ->searchable(['title', 'intro'])
            ->required(false);
    }
}
