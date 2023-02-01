<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

use Filament\Forms\Components\Builder\Block;
use Illuminate\View\Component;

abstract class AbstractContentBlock extends Component
{
    /**
     * Create a new component instance.
     *
     * @param  array|null  $blockData
     */
    abstract public function __construct(?array $blockData);

    abstract public static function getName(): string;

    abstract public static function getIcon(): string;

    public static function getLabel(): string
    {
        $name = static::getName();

        return trans("filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.{$name}.label");
    }

    public static function getFieldLabel(string $field): string
    {
        $name = static::getName();

        return trans("filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.{$name}.{$field}");
    }

    /**
     * Make a new Filament Block instance for this flexible block.
     */
    public static function make(): Block
    {
        return Block::make(static::getName())
            ->label(self::getLabel())
            ->icon(static::getIcon());
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    abstract public function render();
}
