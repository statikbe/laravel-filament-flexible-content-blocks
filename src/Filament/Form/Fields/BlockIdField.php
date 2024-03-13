<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Hidden;
use Illuminate\Support\Str;

class BlockIdField extends Hidden
{
    const FIELD = 'block_id';

    public static function create(): static
    {
        return static::make(static::FIELD)
            ->default(function ($state) {
                return $state ?? static::generateBlockId();
            })
            ->afterStateHydrated(function (Hidden $component, $state) {
                if (! $state) {
                    $component->state(static::generateBlockId());
                }
            });
    }

    public static function generateBlockId(): string
    {
        return Str::random(12);
    }
}
