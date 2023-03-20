<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns;

    use Filament\Forms\Components\Field;
    use Illuminate\Database\Eloquent\Model;

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
                    return 'heroicon-s-translate';
                }

                return null;
            };

            return $this;
        }
    }
