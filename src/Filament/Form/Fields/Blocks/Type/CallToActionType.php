<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type;

use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable;

class CallToActionType extends AbstractType
{
    private bool $isUrlType = false;

    /**
     * @param  class-string<Linkable>|null  $model
     */
    final public function __construct(?string $model)
    {
        $this->model($model);

        $this->setUp();
    }

    /**
     * @param  class-string<Linkable>|null  $model
     */
    public static function make(?string $model): static
    {
        return app(static::class, ['model' => $model]);
    }

    public function setAsUrlType(): self
    {
        $this->isUrlType = true;

        return $this;
    }

    public function isUrlType(): bool
    {
        return $this->isUrlType;
    }

    public function getAlias(): string
    {
        if ($this->isUrlType) {
            return 'url';
        }

        return app($this->getModel())->getMorphClass();
    }
}
