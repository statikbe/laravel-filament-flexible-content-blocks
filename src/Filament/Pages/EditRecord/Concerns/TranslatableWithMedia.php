<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\EditRecord\Concerns;

use Filament\Resources\Pages\EditRecord\Concerns\Translatable;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasTranslatableMedia;

class TranslatableWithMedia
{
    use Translatable;

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

        $this->setActiveLocale($this->oldActiveLocale);

        try {
            $this->form->validate();
        } catch (ValidationException $exception) {
            $this->activeLocale = $this->oldActiveLocale;

            throw $exception;
        }

        $this->setActiveLocale($newActiveLocale);

        if (blank($this->oldActiveLocale)) {
            return;
        }

        $translatableAttributes = app(static::getModel())->getTranslatableAttributes();
        if (method_exists($this->getRecord(), 'getTranslatableMediaCollections')) {
            $translatableAttributes = array_merge($translatableAttributes, $this->getRecord()->getTranslatableMediaCollections());
        }

        $this->data[$newActiveLocale] = array_merge(
            $this->data[$newActiveLocale] ?? [],
            Arr::except(
                $this->data[$this->oldActiveLocale] ?? [],
                $translatableAttributes,
            ),
        );

        $this->data[$this->oldActiveLocale] = Arr::only(
            $this->data[$this->oldActiveLocale] ?? [],
            $translatableAttributes,
        );
    }
}
