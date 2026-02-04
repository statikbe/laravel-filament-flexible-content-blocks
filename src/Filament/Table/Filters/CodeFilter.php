<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Filters;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\CodeField;

class CodeFilter extends SelectFilter
{
    public static function create(): static
    {
        $field = CodeField::FIELD;

        return static::make($field)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.filter.code.label'))
            ->searchable()
            ->options(function (CodeFilter $filter): array {
                $optionsQuery = $filter->getTable()->getQuery();

                return $optionsQuery
                    ->clone()
                    ->select(CodeField::FIELD)
                    ->whereNotNull(CodeField::FIELD)
                    ->where(CodeField::FIELD, '!=', '')
                    ->distinct()
                    ->orderBy(CodeField::FIELD)
                    ->pluck(CodeField::FIELD, CodeField::FIELD)
                    ->toArray();

            })
            ->query(function (Builder $query, array $data) use ($field): Builder {
                if (filled($data['value'])) {
                    return $query->whereRaw("LOWER($field) LIKE ?", ['%'.strtolower($data['value']).'%']);
                }

                return $query;
            });
    }
}
