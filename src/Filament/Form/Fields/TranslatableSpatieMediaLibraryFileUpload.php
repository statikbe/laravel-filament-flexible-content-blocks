<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Component as Livewire;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasImageEditor;

class TranslatableSpatieMediaLibraryFileUpload extends SpatieMediaLibraryFileUpload
{
    use HasImageEditor;

    protected function setUp(): void
    {
        parent::setUp();

        self::addImageEditor($this);

        $this->customProperties(function (Livewire $livewire){
            return $this->getCurrentLocaleFilter($livewire);
        });

        $this->filterMediaUsing(function (Collection $media, Livewire $livewire): Collection {
            $filter = $this->getCurrentLocaleFilter($livewire);
            return $media->filter(function(Media $item) use ($filter) {
                return $item->getCustomProperty('locale') == $filter['locale'];
            });
        });

        //temp until PR is merged:
        $this->loadStateFromRelationshipsUsing(static function (SpatieMediaLibraryFileUpload $component, HasMedia $record): void {
            /** @var Model&HasMedia $record */
            $media = $record->load('media')->getMedia($component->getCollection())
                ->when(
                    $component->hasMediaFilter(),
                    fn (Collection $media) => $component->filterMedia($media)
                )
                ->when(
                    ! $component->isMultiple(),
                    fn (Collection $media): Collection => $media->take(1),
                )
                ->mapWithKeys(function (Media $media): array {
                    $uuid = $media->getAttributeValue('uuid');

                    return [$uuid => $uuid];
                })
                ->toArray();

            $component->state($media);
        });
    }

    private function getCurrentLocaleFilter(Livewire $livewire): array {
        $mediaFilters = [];
        if (method_exists($livewire, 'getActiveFormsLocale')) {
            $mediaFilters['locale'] = $livewire->getActiveFormsLocale();
        }
        return $mediaFilters;
    }
}
