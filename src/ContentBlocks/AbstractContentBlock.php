<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
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
     *
     * @return Block
     */
    public static function make(): Block
    {
        return Block::make(static::getName())
            ->label(static::getLabel())
            ->icon(static::getIcon())
            ->schema(static::makeFilamentSchema());
    }

    /**
     * Returns the block's form schema consisting of an list of Filament form components or a closure that returns such a list.
     *
     * @return array<\Filament\Forms\Components\Component>|Closure
     */
    abstract protected static function makeFilamentSchema(): array|Closure;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    abstract public function render();
}
