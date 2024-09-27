<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\CreateRecord\Concerns;

use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

trait TranslatableWithMedia
{
    use Translatable;

    protected function handleRecordCreation(array $data): Model
    {
        $record = app(static::getModel());

        $translatableAttributes = $this->getTranslatedAttributesWithTranslatableMedia();

        $record->fill(Arr::except($data, $translatableAttributes));

        foreach (Arr::only($data, $translatableAttributes) as $key => $value) {
            $record->setTranslation($key, $this->activeLocale, $value);
        }

        $originalData = $this->data;

        foreach ($this->otherLocaleData as $locale => $localeData) {
            $this->form->fill([
                ...$this->data,
                ...$localeData,
            ]);

            try {
                $this->form->validate();
            } catch (ValidationException $exception) {
                continue;
            }

            $localeData = Arr::only($this->form->getState(), array_keys($localeData));
            $localeData = $this->mutateFormDataBeforeCreate($localeData);

            foreach (Arr::only($localeData, $translatableAttributes) as $key => $value) {
                $record->setTranslation($key, $locale, $value);
            }
        }

        $this->data = $originalData;

        $record->save();

        return $record;
    }

    /**
     * This function is overridden to be able to update the state of translatable media when the form locale switches.
     * It makes use of the getTranslatableMediaCollections() function to include these media collections as translatable
     * and thus change the state when the locale switches.
     *
     * @see HasTranslatableMedia
     */
    public function updatedActiveLocale(string $newActiveLocale): void
    {
        if (blank($this->oldActiveLocale)) {
            return;
        }

        $this->resetValidation();

        $translatableAttributes = $this->getTranslatedAttributesWithTranslatableMedia();

        $this->otherLocaleData[$this->oldActiveLocale] = Arr::only($this->data, $translatableAttributes);

        $this->form->fill([
            ...Arr::except($this->data, $translatableAttributes),
            ...$this->otherLocaleData[$this->activeLocale] ?? [],
        ]);

        unset($this->otherLocaleData[$this->activeLocale]);
    }

    private function getTranslatedAttributesWithTranslatableMedia(): array
    {
        $record = app(static::getModel());
        $translatableAttributes = $record->getTranslatableAttributes();
        if (method_exists($record, 'getTranslatableMediaCollections')) {
            $translatableAttributes = array_merge($translatableAttributes, $record->getTranslatableMediaCollections());
        } else {
            throw new \BadMethodCallException('The model does not implement the HasTranslatableMedia interface. If this is not implemented translated images will not change when the language switch changes.');
        }

        return $translatableAttributes;
    }
}
