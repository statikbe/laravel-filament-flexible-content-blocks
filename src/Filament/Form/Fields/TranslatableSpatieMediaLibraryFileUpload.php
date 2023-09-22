<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Component as Livewire;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TranslatableSpatieMediaLibraryFileUpload extends SpatieMediaLibraryFileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadStateFromRelationshipsUsing(static function (SpatieMediaLibraryFileUpload $component, HasMedia $record, Livewire $livewire): void {
            $mediaFilters = [];
            if (method_exists($livewire, 'getActiveFormsLocale')) {
                $mediaFilters['locale'] = $livewire->getActiveFormsLocale();
            }

            /** @var Model&HasMedia $record */
            $files = $record->load('media')->getMedia($component->getCollection(), $mediaFilters)
                ->when(
                    ! $component->isMultiple(),
                    fn (Collection $files): Collection => $files->take(1),
                )
                ->mapWithKeys(function (Media $file): array {
                    $uuid = $file->getAttributeValue('uuid');

                    return [$uuid => $uuid];
                })
                ->toArray();

            $component->state($files);
        });
    }

    public function getCustomProperties(): array
    {
        //we get the locale properties and combine them with the custom properties set by customisation:
        $localeProperties = $this->evaluate(function (Livewire $livewire) {
            $properties = [];
            if (method_exists($livewire, 'getActiveFormsLocale')) {
                $properties['locale'] = $livewire->getActiveFormsLocale();
            }

            return $properties;
        });
        $customProperties = $this->evaluate($this->customProperties) ?? [];

        return array_merge($customProperties, $localeProperties);
    }

    public function deleteAbandonedFiles(): void
    {
        //this needs to be overwritten to avoid deleting the translated images.
        //we only select images with the active form locale to be abandoned:

        /** @var Model&HasMedia $record */
        $record = $this->getRecord();

        $filters = [];
        if (method_exists($this->getLivewire(), 'getActiveFormsLocale')) {
            $filters['locale'] = $this->getLivewire()->getActiveFormsLocale();
        }

        $record->getMedia($this->getCollection(), $filters)
            ->whereNotIn('uuid', array_keys($this->getState() ?? []))
            ->each(fn (Media $media) => $media->delete());
    }
}
