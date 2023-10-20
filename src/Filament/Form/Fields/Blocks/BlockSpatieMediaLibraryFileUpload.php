<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImage;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

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

    public function saveUploadedFiles(): void {
        parent::saveUploadedFiles();

        $this->storeSavedBlockImages();
    }

    public function deleteAbandonedFiles(): void
    {
        /** @var Model&HasMedia&HasContentBlocks $record */
        $record = $this->getRecord();

        $uuids = $this->getState() ?? [];
        //add the images that already existed, to avoid deleting images that are still needed from a previous iteration
        $contentBlocksComponent = new ContentBlocks($record);
        foreach ($contentBlocksComponent->contentBlocks as $block) {
            /* @var AbstractContentBlock&HasImage $block */
            if ($block::getName() === $this->getCollection()) {
                $imageUuids = $block->getImageUuids();
                foreach ($imageUuids as $imageUuid) {
                    $uuids[$imageUuid] = $imageUuid;
                }
            }
        }

        $this->storeSavedBlockImages($uuids);

        $record
            ->getMedia($this->getCollection())
            ->whereNotIn('uuid', ContentBlocks::$savedImages)
            ->each(function(Media $media) {
                $media->delete();
            });
    }

    /**
     * Keeps track of all images that have been saved in the content blocks to avoid deleting images that are still needed.
     * @param array|null $uuids
     * @return void
     */
    private function storeSavedBlockImages(?array $uuids=null): void {
        if(!$uuids || empty($uuids)){
            $uuids = $this->getState() ?? [];
        }

        ContentBlocks::$savedImages = array_unique(array_merge(array_merge(ContentBlocks::$savedImages, array_keys($uuids)), array_values($uuids)));
    }
}
