<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns;

use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasPageAttributes;

class PublishedColumn extends TextColumn
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
                /** @var Model&HasPageAttributes $record */
                return $record->isPublished() ? static::STATE_PUBLISHED : static::STATE_UNPUBLISHED;
            })
            ->formatStateUsing(function (string $state, Model&HasPageAttributes $record) use ($options): string {
                $formattedState = $options[$state];
                if ($record->willBecomePublished()) {
                    $publishingBeginsAt = $record->getAttribute('publishing_begins_at');
                    if ($publishingBeginsAt) {
                        $info = trans('filament-flexible-content-blocks::filament-flexible-content-blocks.columns.is_published_state_published_future_info', ['date' => $publishingBeginsAt->format('d/m/Y')]);
                        $formattedState = "$formattedState ($info)";
                    }
                }
                if ($record->willBecomeUnpublished()) {
                    $publishingEndsAt = $record->getAttribute('publishing_ends_at');
                    if ($publishingEndsAt) {
                        $info = trans('filament-flexible-content-blocks::filament-flexible-content-blocks.columns.is_published_state_unpublished_future_info', ['date' => $publishingEndsAt->format('d/m/Y')]);
                        $formattedState = "$formattedState ($info)";
                    }
                }

                return $formattedState;
            })
            ->icons([
                static::STATE_PUBLISHED => Heroicon::Eye,
                static::STATE_UNPUBLISHED => Heroicon::EyeSlash,
            ])
            ->colors([
                'success' => static::STATE_PUBLISHED,
                'danger' => static::STATE_UNPUBLISHED,
            ])->badge();
    }
}
