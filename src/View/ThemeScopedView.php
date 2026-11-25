<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View;

use Illuminate\View\View;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

/**
 * A view that renders inside the theme scope of a model, so the components it embeds follow the model's theme.
 * Blade renders views lazily, so the scope has to be opened by the view itself when it renders.
 */
class ThemeScopedView extends View
{
    private ?object $themeModel = null;

    public static function make(string $view, ?object $model): static
    {
        $base = view($view);
        $scoped = new static($base->getFactory(), $base->getEngine(), $base->name(), $base->getPath(), $base->getData());
        $scoped->themeModel = $model;

        return $scoped;
    }

    public function render(?callable $callback = null)
    {
        return FilamentFlexibleBlocksConfig::withTheme($this->themeModel, fn () => parent::render($callback));
    }
}
