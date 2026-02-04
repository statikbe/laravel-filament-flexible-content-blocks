<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Filters;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class CodeFilter extends SelectFilter
{
    public static function create(): static
    {
        return static::make('code')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.filter.code.label'))
            ->searchable()
            ->options(function (CodeFilter $filter): array {
                $optionsQuery = $filter->getTable()->getQuery();

                return $optionsQuery
                    ->clone()
                    ->select('code')
                    ->whereNotNull('code')
                    ->where('code', '!=', '')
                    ->distinct()
                    ->orderBy('code')
                    ->pluck('code', 'code')
                    ->toArray();

            })
            ->query(function (Builder $query, array $data): Builder {
                if (filled($data['value'])) {
                    return $query->where('code', $data['value']);
                }

                return $query;
            });
    }
}
