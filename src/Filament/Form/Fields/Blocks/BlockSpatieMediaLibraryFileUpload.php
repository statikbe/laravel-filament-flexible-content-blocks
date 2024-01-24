<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasImageEditor;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Closure;

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
            $stateUuids = [];
            if(is_string($state)){
                $stateUuids = [$state];
            }
            else if(is_array($state) && !empty($state)) {
                if(Str::isUuid(array_keys($state)[0])) {
                    $stateUuids = array_keys($state);
                }
                else {
                    $stateUuids = array_values($state);
                }
            }

            /** @var Model&HasMedia $record */
            $media = $record->load('media')->getMedia($component->getCollection())
                ->whereIn('uuid', $stateUuids)
                ->when(
                    ! $component->isMultiple(),
                    fn (Collection $media): Collection => $media->take(1),
                )
                ->when(
                    $component->hasMediaFilter(),
                    fn (Collection $media) => $component->filterMedia($media)
                )
                ->mapWithKeys(function (Media $media): array {
                    $uuid = $media->getAttributeValue('uuid');

                    return [$uuid => $uuid];
                })
                ->toArray();

            $component->state($media);
        });

        //Make sure the uuid of the image is added to the form data
        $this->dehydrated(true);
    }

    public function getState(): mixed
    {
        $state = parent::getState();
        //transform strings to arrays since the upload field expects an array of UUIDs:
        if($state && !is_array($state)){
            $state = array_filter([$state]);
        }
        return $state;
    }

    /**
     * @return array<mixed>
     */
    public function getValidationRules(): array
    {
        $rules = [
            $this->getRequiredValidationRule(),
            'array',
        ];

        if (filled($count = $this->getMaxFiles())) {
            $rules[] = "max:{$count}";
        }

        if (filled($count = $this->getMinFiles())) {
            $rules[] = "min:{$count}";
        }

        $rules[] = function (string $attribute, array|string $value, Closure $fail): void {
            if(is_string($value)){
                $value = [$value];
            }

            $files = array_filter($value, fn (TemporaryUploadedFile | string $file): bool => $file instanceof TemporaryUploadedFile);

            $name = $this->getName();

            $validator = Validator::make(
                [$name => $files],
                ["{$name}.*" => ['file', ...parent::getValidationRules()]],
                [],
                ["{$name}.*" => $this->getValidationAttribute()],
            );

            if (! $validator->fails()) {
                return;
            }

            $fail($validator->errors()->first());
        };

        return $rules;
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
