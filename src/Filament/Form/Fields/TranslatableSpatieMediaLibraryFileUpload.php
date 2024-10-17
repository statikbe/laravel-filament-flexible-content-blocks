<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Support\Collection;
use Livewire\Component as Livewire;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasImageEditor;

class TranslatableSpatieMediaLibraryFileUpload extends SpatieMediaLibraryFileUpload
{
    use HasImageEditor;

    protected function setUp(): void
    {
        parent::setUp();

        self::addImageEditor($this);

        $this->customProperties(function (Livewire $livewire) {
            return $this->getCurrentLocaleFilter($livewire);
        });

        $this->filterMediaUsing(function (Collection $media, Livewire $livewire): Collection {
            $filter = $this->getCurrentLocaleFilter($livewire);

            return $media->filter(function (Media $item) use ($filter) {
                return $item->getCustomProperty('locale') == $filter['locale'];
            });
        });
    }

    private function getCurrentLocaleFilter(Livewire $livewire): array
    {
        $mediaFilters = [];
        if (method_exists($livewire, 'getActiveFormsLocale')) {
            $mediaFilters['locale'] = $livewire->getActiveFormsLocale();
        }

        return $mediaFilters;
    }
}
