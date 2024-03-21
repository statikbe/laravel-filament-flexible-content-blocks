<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Closure;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockIdField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasImageEditor;

/**
 * An extension to the spatie media-library field of Filament to also allow to save the UUID to a block.
 *
 * @see https://github.com/filamentphp/filament/issues/1284
 */
class BlockSpatieMediaLibraryFileUpload extends SpatieMediaLibraryFileUpload
{
    use Filament\Forms\Components\Concerns\CanBeValidated {
        getValidationRules as getValidationRulesTrait;
    }
    use HasImageEditor;

    protected function setUp(): void
    {
        parent::setUp();

        self::addImageEditor($this);

        $this->customProperties(function (Get $get) {
            return ['block' => $get(BlockIdField::FIELD)];
        });

        $this->filterMediaUsing(function (Collection $media, Get $get): Collection {
            return $media->filter(function (Media $item) use ($get) {
                return $item->getCustomProperty('block') === $get(BlockIdField::FIELD);
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

        //Make sure the uuid of the image is added to the form data
        $this->dehydrated(true);
    }

    public function getState(): mixed
    {
        //if the language switch is used, the form data needs to reset with a UUID in an array.
        //often a string UUID is returned by getState() so this function is overwritten to wrap string UUIDs in an array:
        $state = parent::getState();
        //transform strings to arrays since the upload field expects an array of UUIDs:
        if ($state && ! is_array($state)) {
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

        //Changed to support the language switch
        $rules[] = function (string $attribute, array|string|TemporaryUploadedFile $value, Closure $fail): void {
            //Changed: Transform $value to array
            if (is_string($value) || $value instanceof TemporaryUploadedFile) {
                $value = [$value];
            }

            $files = array_filter($value, fn (TemporaryUploadedFile|string $file): bool => $file instanceof TemporaryUploadedFile);

            $name = $this->getName();

            $validator = Validator::make(
                [$name => $files],
                ["{$name}.*" => ['file', ...$this->getValidationRulesTrait()]], //Changed to load validation rules of trait (see on top of class) instead of the rules from BaseFileUpload.
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
}
