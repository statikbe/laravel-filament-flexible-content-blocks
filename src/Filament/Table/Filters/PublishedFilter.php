<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Filters;

use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;

class PublishedFilter extends TernaryFilter
{
    public static function create(): static
    {
        return static::make('is_published')
            ->nullable()
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.filter.is_published.label'))
            ->trueLabel(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.filter.is_published.published_label'))
            ->falseLabel(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.filter.is_published.unpublished_label'))
            ->queries(
                true: function (Builder $query): Builder {
                    if (method_exists($query, 'published')) {
                        return $query->published();
                    }

                    return $query;
                },
                false: function (Builder $query): Builder {
                    if (method_exists($query, 'unpublished')) {
                        return $query->unpublished();
                    }

                    return $query;
                },
                blank: fn (Builder $query): Builder => $query,
            );
    }
}
