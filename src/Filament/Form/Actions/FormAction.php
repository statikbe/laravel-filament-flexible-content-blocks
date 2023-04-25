<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Actions;

use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Field;
use Illuminate\Support\Arr;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

/**
 * Based on awcodes drop-in-action component
 *
 * @see https://github.com/awcodes/drop-in-action
 */
class FormAction extends Field
{
    /** @var array<Action|Closure> */
    protected array $actions = [];

    private array $evaluatedActions = [];

    protected function setUp(): void
    {
        $this->dehydrated(false);

        $themePrefix = FilamentFlexibleBlocksConfig::getViewThemePrefix();

        $this->view("filament-flexible-content-blocks::components.{$themePrefix}form-action");
    }

    public function execute(array|Action|Closure|null $actions): static
    {
        foreach (Arr::wrap($actions) as $action) {
            $this->actions[] = $action;
        }

        return $this;
    }

    /** @return array<Action|Closure> */
    public function getExecutableActions(bool $reevaluate = false): array
    {
        if ((! $reevaluate) && $this->evaluatedActions) {
            return $this->evaluatedActions;
        }

        $this->evaluatedActions = [];

        foreach ($this->actions as $action) {
            $this->evaluatedActions[] = $this->evaluate($action)?->component($this);
        }

        return $this->evaluatedActions;
    }

    public function getActions(): array
    {
        $actions = collect($this->getExecutableActions())
            ->mapWithKeys(fn ($action) => [$action->getName() => $action->component($this)])
            ->toArray();

        return array_merge(parent::getActions(), $actions);
    }
}
