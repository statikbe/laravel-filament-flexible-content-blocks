<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns;

use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasPageAttributes;

class PublishedColumn extends BadgeColumn
{
    const STATE_PUBLISHED = 'published';

    const STATE_UNPUBLISHED = 'unpublished';

    public static function create(): static
    {
        $options = [
            static::STATE_PUBLISHED => trans('filament-flexible-content-blocks::filament-flexible-content-blocks.columns.is_published_state_published'),
            static::STATE_UNPUBLISHED => trans('filament-flexible-content-blocks::filament-flexible-content-blocks.columns.is_published_state_unpublished'),
        ];

        return static::make('is_published')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.columns.is_published'))
            ->getStateUsing(function (Model $record) {
                if ($record->isPublished()) {
                    return static::STATE_PUBLISHED;
                } else {
                    return static::STATE_UNPUBLISHED;
                }
            })
            ->enum($options)
            ->formatStateUsing(function (BadgeColumn $column, string $state) use ($options): string {
                $record = $column->getRecord();
                $formattedState = $options[$state];
                if ($record->willBecomePublished()) {
                    $info = trans('filament-flexible-content-blocks::filament-flexible-content-blocks.columns.is_published_state_published_future_info', ['date' => $record->publishing_begins_at->format('d/m/Y')]);
                    $formattedState = "$formattedState ($info)";
                }
                if ($record->willBecomeUnpublished()) {
                    $info = trans('filament-flexible-content-blocks::filament-flexible-content-blocks.columns.is_published_state_unpublished_future_info', ['date' => $record->publishing_ends_at->format('d/m/Y')]);
                    $formattedState = "$formattedState ($info)";
                }

                return $formattedState;
            })
            ->icons([
                'heroicon-o-eye' => static::STATE_PUBLISHED,
                'heroicon-o-eye-off' => static::STATE_UNPUBLISHED,
            ])
            ->colors([
                'success' => static::STATE_PUBLISHED,
                'danger' => static::STATE_UNPUBLISHED,
            ]);
    }
}
