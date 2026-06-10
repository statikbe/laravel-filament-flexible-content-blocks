<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type\AbstractType;

/**
 * Concrete AbstractType used by the test suite. It exposes the protected query
 * helpers so the locale-aware search/ordering behaviour can be asserted in
 * isolation.
 */
class TestableType extends AbstractType
{
    public function __construct(string $model)
    {
        $this->model($model);

        $this->setUp();
    }

    public static function make(string $model): static
    {
        return new static($model);
    }

    public function exposeResolveColumnReference(Model $model, string $column, ?string $locale): string
    {
        return $this->resolveColumnReference($model, $column, $locale);
    }

    public function exposeApplyDefaultOrder(Builder $query, ?string $locale = null): Builder
    {
        $this->applyDefaultOrder($query, $locale);

        return $query;
    }

    /**
     * @return array<int, string>
     */
    public function exposeGetExistingSearchColumns(Builder $query): array
    {
        return $this->getExistingSearchColumns($query);
    }
}
