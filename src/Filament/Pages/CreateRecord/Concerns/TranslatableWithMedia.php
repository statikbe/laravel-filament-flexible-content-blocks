<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\CreateRecord\Concerns;

use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;
use Illuminate\Support\Arr;

trait TranslatableWithMedia
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
        $this->setActiveLocale($newActiveLocale);

        if (blank($this->oldActiveLocale)) {
            return;
        }

        $record = app(static::getModel());
        $translatableAttributes = $record->getTranslatableAttributes();
        if (method_exists($record, 'getTranslatableMediaCollections')) {
            $translatableAttributes = array_merge($translatableAttributes, $record->getTranslatableMediaCollections());
        } else {
            throw new \BadMethodCallException('The model does not implement the HasTranslatableMedia interface. If this is not implemented translated images will not change when the language switch changes.');
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
