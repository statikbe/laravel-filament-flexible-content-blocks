<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns;

use Filament\Tables\Columns\TextColumn;

/**
 * Text column with a limit of 50 and a tooltip that shows the whole title if it is too long.
 */
class TitleColumn extends TextColumn
{
    public static function create(): static
    {
        $field = static::getFieldName();

        return static::make($field)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.columns.title'))
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
            ->sortable();
    }

    public static function getFieldName(): string
    {
        return 'title';
    }
}
