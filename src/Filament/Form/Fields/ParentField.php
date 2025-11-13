<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasPageAttributes;

class ParentField extends Select
{
    const FIELD = 'parent_id';

    public static function create(): static
    {
        return static::make(static::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.parent_lbl'))
            ->helperText(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.parent_help'))
            ->relationship(name: 'parent', titleAttribute: null)
            ->getOptionLabelFromRecordUsing(fn (Model&HasPageAttributes $record) => $record->getAttribute('title'))
            ->searchable(['title', 'intro'])
            ->required(false);
    }
}
