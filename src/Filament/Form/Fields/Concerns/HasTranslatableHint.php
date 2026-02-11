<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns;

use Filament\Forms\Components\Field;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

/**
 * Checks if the field is translatable and adds a default translatable hint, if no other hint is provided.
 */
trait HasTranslatableHint
{
    public function addsTranslatableHint(): static
    {
        $this->hint = function (?Model $record, Field $component) {
            if ($record && isset($record->translatable) && in_array($component->getName(), $record->translatable)) {
                return trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.translatable_hint');
            }

            return null;
        };

        $this->hintIcon = function (?Model $record, Field $component) {
            if ($record && isset($record->translatable) && in_array($component->getName(), $record->translatable)) {
                return Heroicon::Language;
            }

            return null;
        };

        return $this;
    }

    protected function hasTranslatableField(?Model $record, Field $component, Component $livewire): bool
    {
        if (method_exists($livewire, 'getTranslatableLocales') && count($livewire->getTranslatableLocales()) <= 1) {
            return false;
        }

        return $record && isset($record->translatable) && in_array($component->getName(), $record->translatable);
    }
}
