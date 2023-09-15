<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns;

use Filament\Tables\Columns\TextColumn;

/**
 * Text column with a limit of 50 and a tooltip that shows the whole title if it is too long.
 */
class TitleColumn extends \Filament\Tables\Columns\TextColumn
{
    public static function create(): static
    {
        return static::make('title')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.columns.title'))
            ->limit(50)
            ->tooltip(function (TextColumn $column): ?string {
                $state = $column->getState();

                if (strlen($state) <= $column->getCharacterLimit()) {
                    return null;
                }

                // Only render the tooltip if the column contents exceeds the length limit.
                return $state;
            })
            ->sortable();
    }
}
