<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class AuthorField extends Select
{
    const FIELD = 'author_id';

    public static function create(): static
    {
        return self::make(self::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.author_lbl'))
            ->relationship('author', 'name')
            ->default(Auth::user()->id)
            ->searchable()
            //this is a default search query, add your own implementation by implementing getSearchResultsUsing on creation of the field.
            ->getSearchResultsUsing(fn (string $searchQuery) => config('filament-flexible-content-blocks.author_model', 'Illuminate\Foundation\Auth\User')::query()
                    //TODO make search fields configurable
                    ->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('email', 'like', "%{$searchQuery}%")
                    ->orderBy('name', 'asc')
                    ->limit(50)
                    ->pluck('name', 'id'));
    }
}
