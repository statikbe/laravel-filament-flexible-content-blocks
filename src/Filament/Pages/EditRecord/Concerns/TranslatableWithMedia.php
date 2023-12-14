<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\EditRecord\Concerns;

use Filament\Resources\Pages\EditRecord\Concerns\Translatable;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasTranslatableMedia;

trait TranslatableWithMedia
{
    use Translatable;

    public function save(bool $shouldRedirect = true): void
    {
        $this->authorizeAccess();

        $originalActiveLocale = $this->activeLocale;

        try {
            /** @internal Read the DocBlock above the following method. */
            $this->validateFormAndUpdateRecordAndCallHooks();

            $nonTranslatableData = Arr::except(
                $this->data[$originalActiveLocale] ?? [],
                $this->getTranslatedAttributesWithTranslatableMedia()
            );

            $otherTranslatableLocales = Arr::except($this->getTranslatableLocales(), $originalActiveLocale);

            foreach ($otherTranslatableLocales as $locale) {
                $this->setActiveLocale($locale);

                $this->data[$locale] = array_merge(
                    $this->data[$locale] ?? [],
                    $nonTranslatableData,
                );

                /** @internal Read the DocBlock above the following method. */
                $this->validateFormAndUpdateRecordAndCallHooks();
            }
        } catch (Halt $exception) {
            return;
        }

        $this->setActiveLocale($originalActiveLocale);

        /** @internal Read the DocBlock above the following method. */
        $this->sendSavedNotificationAndRedirect(shouldRedirect: $shouldRedirect);
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

        $translatableAttributes = $this->getTranslatedAttributesWithTranslatableMedia();

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

    private function getTranslatedAttributesWithTranslatableMedia(): array
    {
        $translatableAttributes = app(static::getModel())->getTranslatableAttributes();
        if (method_exists($this->getRecord(), 'getTranslatableMediaCollections')) {
            $translatableAttributes = array_merge($translatableAttributes, $this->getRecord()->getTranslatableMediaCollections());
        } else {
            throw new \BadMethodCallException('The model does not implement the HasTranslatableMedia interface. If this is not implemented translated images will not change when the language switch changes.');
        }

        return $translatableAttributes;
    }
}
