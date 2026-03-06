<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Select;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            ->default(Auth::user()->getKey())
            ->searchable()
            // this is a default search query, add your own implementation by implementing getSearchResultsUsing on creation of the field.
            ->getSearchResultsUsing(function (string $searchQuery) {
                $searchQuery = trim(Str::lower($searchQuery));
                $authorNameColumn = FilamentFlexibleBlocksConfig::getAuthorNameColumn();

                /** @var Model $model */
                $model = app(FilamentFlexibleBlocksConfig::getAuthorModel());
                $query = $model::query();

                /** @var Connection $databaseConnection */
                $databaseConnection = $query->getConnection();
                $grammar = $databaseConnection->getQueryGrammar();

                $searchOperator = match ($databaseConnection->getDriverName()) {
                    'pgsql' => 'ilike',
                    default => 'like',
                };

                return $query
                    ->where(function (Builder $query) use ($searchQuery, $authorNameColumn, $grammar, $searchOperator) {
                        $query->where(
                            DB::raw('lower('.$grammar->wrap($authorNameColumn).')'),
                            $searchOperator,
                            "%{$searchQuery}%",
                        )->orWhere(
                            DB::raw('lower('.$grammar->wrap('email').')'),
                            $searchOperator,
                            "%{$searchQuery}%",
                        );
                    })
                    ->orderBy($authorNameColumn, 'asc')
                    ->limit(50)
                    ->pluck($authorNameColumn, $model->getQualifiedKeyName());
            });
    }
}
