<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns;

use Filament\Tables\Columns\TextColumn;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\CodeField;

class CodeColumn extends TextColumn
{
    public static function create(): static
    {
        $field = static::getFieldName();

        return static::make($field)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.columns.code'))
            ->limit(50)
            ->tooltip(function (TextColumn $column): ?string {
                // in case the label is larger than the limit, we show it fully in a tooltip:
                $state = $column->getState();

                if (strlen($state) <= $column->getCharacterLimit()) {
                    return null;
                }

                // Only render the tooltip if the column content exceeds the length limit.
                return $state;
            })
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true)
            ->searchable();
    }

    public static function getFieldName(): string
    {
        return CodeField::FIELD;
    }
}
