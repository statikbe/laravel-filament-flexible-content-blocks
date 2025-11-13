<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Concerns\PromptsForMissingInput as PromptsForMissingInputTrait;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockIdField;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

/**
 * This command upgrades the content blocks data model to support the v1.0 features of this package.
 * Hence, this command should only be executed if you are upgrading from a version of this package < 1.0.
 *
 * The command adds `block_id` variables to each block with a unique UUID that is used to link image fields to a block.
 * Spatie Medialibrary uses a polymorph relationship with the model that has the content blocks. But this makes it
 * difficult to link n images to different blocks of 1 model. The command adds the block id to the custom properties of
 * the media record. This data is then used to link a media record to a specific block.
 *
 * The versions < 1.0 also had issues with copying the content blocks to different locales via the action. Namely, the
 * the whole content blocks data was just copied to the different locales. This resulted in lost media for all locales,
 * if the media was removed in one of the locales. The command also corrects this and makes a copy of the image in blocks,
 * to make sure each image is uniaue per block per locale.
 */
class UpgradeSpatieImageFieldsCommand extends Command implements PromptsForMissingInput
{
    use PromptsForMissingInputTrait;

    public $signature = 'filament-flexible-content-blocks:upgrade-images
                         {model : The model class with namespace (use double backslashes)}
                         {--customimage= : Custom image field names}';

    public $description = 'Upgrade content blocks data field to add block IDs to link the image fields properly to blocks.';

    protected array $imageFields = ['image'];

    public function handle(): int
    {
        /** @var class-string<Model&HasContentBlocks> $modelClass */
        $modelClass = $this->argument('model');

        $customImage = $this->option('customimage');
        if ($customImage) {
            $this->imageFields[] = $customImage;
        }

        $modelClass::orderBy('id', 'desc')->chunk(50, function (Collection $models) {
            foreach ($models as $model) {
                /** @var Model $model */
                // check if the model is translated:
                if (isset($model->translatable) && in_array('content_blocks', $model->translatable)) {
                    foreach (FilamentFlexibleContentBlocks::getLocales() as $locale) {
                        $contentBlocks = $model->getTranslation('content_blocks', $locale); // @phpstan-ignore-line
                        $upgradedContentBlocks = $this->upgradeContentBlocks($contentBlocks, $model);

                        $model->setTranslation('content_blocks', $locale, $upgradedContentBlocks); // @phpstan-ignore-line
                        $model->save();
                    }
                } else {
                    $model->setAttribute('content_blocks', $this->upgradeContentBlocks($model->getAttribute('content_blocks'), $model));

                    // save upgrade
                    $model->save();
                }

                $this->comment("Model {$model->getKey()} upgraded.");
            }
        });

        $this->comment('Upgrade done!');

        return self::SUCCESS;
    }

    public function upgradeContentBlocks(array $contentBlocks, Model&HasContentBlocks&HasMedia $model): array
    {
        foreach ($contentBlocks as &$block) {
            // add block id to each block:
            if (! isset($block['data'][BlockIdField::FIELD])) {
                $block['data'][BlockIdField::FIELD] = BlockIdField::generateBlockId();
            }

            foreach ($this->imageFields as $imageField) {
                if (isset($block['data'][$imageField]) && $block['data'][$imageField]) {
                    $block['data'][$imageField] = $this->updateMedia($block['data'][$imageField], $block['data'][BlockIdField::FIELD], $model);
                }
            }

            // cards:
            if ($block['type'] === 'filament-flexible-content-blocks::cards') {
                if (isset($block['data']['cards'])) {
                    foreach ($block['data']['cards'] as $card) {
                        if (! isset($card[BlockIdField::FIELD])) {
                            $card[BlockIdField::FIELD] = BlockIdField::generateBlockId();
                            foreach ($this->imageFields as $imageField) {
                                if (isset($card[$imageField]) && $card[$imageField]) {
                                    $card[$imageField] = $this->updateMedia($card[$imageField], $card[BlockIdField::FIELD], $model);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $contentBlocks;
    }

    private function updateMedia(?string $mediaUuid, string $blockId, Model&HasMedia $model): string
    {
        if ($mediaUuid) {
            $media = Media::findByUuid($mediaUuid);
            if ($media) {
                // Check if there is already a block assigned. If so, this means it is already used by another block.
                // This is possible because of the old copy content blocks button to other locales, that did not copy media for each locale.
                // @phpstan-ignore-next-line
                if ($media->getCustomProperty('block')) {
                    // make a copy of the media:
                    // @phpstan-ignore-next-line
                    $media = $media->copy($model, $media->collection_name, $media->disk);
                }
                // add block id as custom property to media:
                $media->setCustomProperty('block', $blockId);
                $media->save();

                return $media->uuid;
            }
        }

        return $mediaUuid;
    }
}
