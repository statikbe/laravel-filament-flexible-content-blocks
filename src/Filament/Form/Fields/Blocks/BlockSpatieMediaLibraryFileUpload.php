<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasImageEditor;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

/**
 * An extension to the spatie media-library field of Filament to also allow to save the UUID to a block.
 *
 * @see https://github.com/filamentphp/filament/issues/1284
 */
class BlockSpatieMediaLibraryFileUpload extends SpatieMediaLibraryFileUpload
{
    use HasImageEditor;

    protected function setUp(): void
    {
        parent::setUp();

        self::addImageEditor($this);

        //Extend the media library load logic to also look at the given state (in our case the uuids of the images)
        $this->loadStateFromRelationshipsUsing(static function (SpatieMediaLibraryFileUpload $component, HasMedia $record, $state): void {
            if (is_string($state)) {
                $state = [$state];
            }

            $files = [];
            if (is_array($state)) {
                $files = $record->load('media')->getMedia($component->getCollection())
                    ->whereIn('uuid', $state)
                    ->when(
                        ! $component->isMultiple(),
                        fn (Collection $files): Collection => $files->take(1),
                    )
                    ->mapWithKeys(function (Media $file): array {
                        $uuid = $file->getAttributeValue('uuid');

                        return [$uuid => $uuid];
                    })
                    ->toArray();
            }

            $component->state($files);
        });

        //Make sure the uuid of the image is added to the form data
        $this->dehydrated(true);
    }

    public function deleteAbandonedFiles(): void
    {
        //NOTE: to solve the issue of deleting media that is still needed, it might be necessary to make the media collection
        //unique per block. We could add another explicit block variable with a UUID and append this UUID to the media
        //collection name, so we can create a unique media collection per block.
        /** @var Model&HasMedia&HasContentBlocks $record */
        $record = $this->getRecord();

        //we get all the UUIDs that are in the form. This is a superset of the actual image UUIDs used by the block,
        //but since UUIDs should be unique, it does not matter that we filter out UUIDs of other blocks, since the
        //media collection will filter those out anyway.
        //By using the livewire form data, we have the latest status and include all languages.
        $formData = $this->getLivewire()->data;
        $allUuids = collect($formData)->dot()->filter(function ($value, $key) {
            return Str::isUuid($value);
        })->values();

        $record
            ->getMedia($this->getCollection())
            ->whereNotIn('uuid', $allUuids)
            ->each(function (Media $media) {
                $media->delete();
            });
    }
}
