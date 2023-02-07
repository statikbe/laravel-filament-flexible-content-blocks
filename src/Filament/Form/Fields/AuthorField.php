<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use App\Models\User;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class AuthorField extends Select
{
    public static function create(): static
    {
        return self::make('author_id')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.author_lbl'))
            ->relationship('author', 'name')
            ->default(Auth::user()->id)
            ->searchable()
            ->getSearchResultsUsing(fn (string $searchQuery) => User::query()->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('email', 'like', "%{$searchQuery}%")
                    ->orderBy('name', 'asc')
                    ->limit(50)
                    ->pluck('name', 'id'));
    }
}
