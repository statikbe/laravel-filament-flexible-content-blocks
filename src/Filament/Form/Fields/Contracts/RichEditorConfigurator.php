<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Contracts;

use Filament\Forms\Components\Field;

interface RichEditorConfigurator
{
    /**
     * Create and configure a rich editor field.
     *
     * @param  class-string|null  $blockClass
     */
    public function make(string $name, ?string $blockClass = null): Field;
}
