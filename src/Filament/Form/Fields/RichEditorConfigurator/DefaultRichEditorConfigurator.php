<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\RichEditorConfigurator;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\RichEditor;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Contracts\RichEditorConfigurator;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\FlexibleRichEditorField;

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

    public function toPlainText(mixed $content): string
    {
        if (! is_string($content)) {
            return '';
        }

        return strip_tags($content);
    }
}
