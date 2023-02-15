<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type;

use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes;

class OverviewType extends AbstractType
{
    /**
     * @param  class-string<HasOverviewAttributes>  $model
     */
    final public function __construct(string $model)
    {
        $this->model($model);

        $this->setUp();
    }

    /**
     * @param  class-string<HasOverviewAttributes>  $model
     */
    public static function make(string $model): static
    {
        return app(static::class, ['model' => $model]);
    }
}
