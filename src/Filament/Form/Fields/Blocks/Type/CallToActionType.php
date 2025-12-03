<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type;

use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable;

class CallToActionType extends AbstractType
{
    const TYPE_URL = 'url';

    const TYPE_ROUTE = 'route';

    private bool $isUrlType = false;

    private bool $isRouteType = false;

    /**
     * @param  class-string<Linkable>|null  $model
     */
    final public function __construct(?string $model)
    {
        $this->model($model);

        $this->setUp();
    }

    protected function setUp(): void
    {
        parent::setUp();

        // use the translated title attribute:
        $this->getOptionLabelFromRecordUsing(function (Model $record, ?string $locale = null): string {
            return method_exists($record, 'translate') ? $record->translate($this->getTitleColumnName(), $locale ?? app()->getLocale()) : $record->{$this->getTitleColumnName()};
        });
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

    public function setAsRouteType(): self
    {
        $this->isRouteType = true;

        return $this;
    }

    public function isRouteType(): bool
    {
        return $this->isRouteType;
    }

    public function getAlias(): string
    {
        if ($this->isUrlType) {
            return static::TYPE_URL;
        }

        if ($this->isRouteType) {
            return static::TYPE_ROUTE;
        }

        return app($this->getModel())->getMorphClass();
    }
}
