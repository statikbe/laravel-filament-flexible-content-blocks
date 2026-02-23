<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\RichEditor;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Contracts\RichEditorConfigurator;

class DefaultRichEditorConfigurator implements RichEditorConfigurator
{
    /**
     * @param  class-string|null  $blockClass
     */
    public function make(string $name, ?string $blockClass = null): Field
    {
        $editor = RichEditor::make($name);

        $toolbarButtons = FlexibleRichEditorField::getToolbarButtons($blockClass);
        if ($toolbarButtons !== null) {
            $editor->toolbarButtons($toolbarButtons);
        } else {
            $disabledButtons = FlexibleRichEditorField::getDisabledToolbarButtons($blockClass);
            if ($disabledButtons !== null) {
                $editor->disableToolbarButtons($disabledButtons);
            }
        }

        return $editor;
    }
}
