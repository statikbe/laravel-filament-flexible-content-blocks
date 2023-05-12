<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Filters;

use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;

class PublishedFilter extends TernaryFilter
{
    public static function create(): static {
        return static::make('is_published')
            ->nullable()
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.filter.is_published.label'))
            ->trueLabel(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.filter.is_published.published_label'))
            ->falseLabel(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.filter.is_published.unpublished_label'))
            ->queries(
                true: fn (Builder $query): Builder => $query->published(),
                false: fn (Builder $query): Builder => $query->unpublished(),
                blank: fn (Builder $query): Builder => $query,
            );
    }
}
