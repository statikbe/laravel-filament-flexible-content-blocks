<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Field;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasTranslatableHint;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Contracts\RichEditorConfigurator;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

class FlexibleRichEditorField
{
    use HasTranslatableHint;

    /**
     * @param  class-string|null  $blockClass
     */
    public static function create(string $name, ?string $blockClass = null): Field
    {
        return static::getConfigurator($blockClass)->make($name, $blockClass);
    }

    /**
     * @param  class-string|null  $blockClass
     */
    public static function createTranslatable(string $name, ?string $blockClass = null): Field
    {
        return static::applyTranslatableHint(static::create($name, $blockClass));
    }

    /**
     * @param  class-string|null  $blockClass
     */
    protected static function getConfigurator(?string $blockClass = null): RichEditorConfigurator
    {
        $class = FilamentFlexibleBlocksConfig::getRichEditorConfigurator($blockClass);

        return app($class);
    }

    /**
     * @param  class-string|null  $blockClass
     * @return string[]|null
     */
    public static function getToolbarButtons(?string $blockClass = null): ?array
    {
        return FilamentFlexibleBlocksConfig::getRichEditorToolbarButtons($blockClass);
    }

    /**
     * @param  class-string|null  $blockClass
     * @return string[]|null
     */
    public static function getDisabledToolbarButtons(?string $blockClass = null): ?array
    {
        return FilamentFlexibleBlocksConfig::getDisabledRichEditorToolbarButtons($blockClass);
    }
}
