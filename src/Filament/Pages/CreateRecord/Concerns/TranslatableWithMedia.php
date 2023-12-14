<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\CreateRecord\Concerns;

use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

trait TranslatableWithMedia
{
    use Translatable;

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = [
                $this->activeLocale => $this->form->getState(),
            ];

            $this->callHook('afterValidate');

            $originalActiveLocale = $this->activeLocale;

            $translatableAttributes = $this->getTranslatedAttributesWithTranslatableMedia();

            $nonTranslatableData = Arr::except(
                $this->data[$originalActiveLocale] ?? [],
                $translatableAttributes,
            );

            foreach ($this->getTranslatableLocales() as $locale) {
                if ($locale === $originalActiveLocale) {
                    continue;
                }

                try {
                    $this->setActiveLocale($locale);

                    $this->data[$locale] = array_merge(
                        $this->data[$locale] ?? [],
                        $nonTranslatableData,
                    );

                    $this->callHook('beforeValidate');

                    $data[$locale] = $this->form->getState();

                    $this->callHook('afterValidate');
                } catch (ValidationException $exception) {
                    // If the validation fails for a non-active locale,
                    // we'll just ignore it and continue, since it's
                    // likely that the user hasn't filled out the
                    // required fields for that locale.
                }
            }

            $this->setActiveLocale($originalActiveLocale);

            foreach ($data as $locale => $localeData) {
                if ($locale !== $this->activeLocale) {
                    $localeData = Arr::only(
                        $localeData,
                        $translatableAttributes,
                    );
                }

                $data[$locale] = $this->mutateFormDataBeforeCreate($localeData);
            }

            /** @internal Read the DocBlock above the following method. */
            $this->createRecordAndCallHooks($data);
        } catch (Halt $exception) {
            return;
        }

        /** @internal Read the DocBlock above the following method. */
        $this->sendCreatedNotificationAndRedirect(shouldCreateAnotherInsteadOfRedirecting: $another);
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
