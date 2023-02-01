<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * An extension to the spatie media-library field of Filament to also allow to save the UUID to a block.
 *
 * @see https://github.com/filamentphp/filament/issues/1284
 */
class BlockSpatieMediaLibraryFileUpload extends SpatieMediaLibraryFileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        //Extend the media library load logic to also look at the given state (in our case the uuids of the images)
        $this->loadStateFromRelationshipsUsing(static function (SpatieMediaLibraryFileUpload $component, HasMedia $record, $state): void {
            if (is_string($state)) {
                $state = [$state];
            }

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
            } else {
                /** @var Model&HasMedia $record */
                $files = $record->load('media')->getMedia($component->getCollection())
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
}
