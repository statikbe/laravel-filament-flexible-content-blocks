<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

class AuthorField extends Select
{
    const FIELD = 'author_id';

    public static function create(): static
    {
        return static::make(static::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.author_lbl'))
            ->relationship('author', 'name')
            ->default(Auth::user()->id)
            ->searchable()
            // this is a default search query, add your own implementation by implementing getSearchResultsUsing on creation of the field.
            ->getSearchResultsUsing(function (string $searchQuery) {
                $searchQuery = trim(Str::lower($searchQuery));
                $authorNameColumn = FilamentFlexibleBlocksConfig::getAuthorNameColumn();

                return FilamentFlexibleBlocksConfig::getAuthorModel()::query()
                    ->whereRaw("LOWER(`{$authorNameColumn}`) LIKE ? ", "%{$searchQuery}%")
                    ->orWhereRaw('LOWER(`email`) LIKE ? ', "%{$searchQuery}%")
                    ->orderBy($authorNameColumn, 'asc')
                    ->limit(50)
                    ->pluck($authorNameColumn, 'id');
            });
    }
}
