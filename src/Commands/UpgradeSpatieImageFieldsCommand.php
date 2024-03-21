<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Concerns\PromptsForMissingInput as PromptsForMissingInputTrait;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Database\Eloquent\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockIdField;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

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
        $model = $this->argument('model');

        $customImage = $this->option('customimage');
        if($customImage){
            $this->imageFields[] = $customImage;
        }

        $model::orderBy('id', 'desc')->limit(2)->chunk(2, function (Collection $models) {
            foreach ($models as $model) {
                /* @var HasContentBlocks $model */
                //check if the model is translated:
                if (isset($model->translatable) && in_array('content_blocks', $model->translatable)) {
                    foreach (FilamentFlexibleContentBlocks::getLocales() as $locale) {
                        $contentBlocks = $model->getTranslation('content_blocks', $locale);
                        $upgradedContentBlocks = $this->upgradeContentBlocks($contentBlocks);
                        //$this->comment(json_encode($upgradedContentBlocks, JSON_PRETTY_PRINT));

                        $model->setTranslation('content_blocks', $locale, $upgradedContentBlocks);
                        $model->save();
                    }
                } else {
                    $model->content_blocks = $this->upgradeContentBlocks($model->content_blocks);

                    //save upgrade
                    $model->save();
                }

                $this->comment("Model {$model->id} upgraded.");
                //$this->comment(json_encode($model->content_blocks , JSON_PRETTY_PRINT));
            }
        });

        $this->comment('Upgrade done!');

        return self::SUCCESS;
    }

    // Generate function
    public function upgradeContentBlocks(array $contentBlocks): array
    {
        foreach ($contentBlocks as &$block) {
            //add block id to each block:
            if (! isset($block['data'][BlockIdField::FIELD])) {
                $block['data'][BlockIdField::FIELD] = BlockIdField::generateBlockId();
            }

            foreach ($this->imageFields as $imageField) {
                if (isset($block['data'][$imageField]) && $block['data'][$imageField]) {
                    $this->updateMedia($block['data'][$imageField], $block['data'][BlockIdField::FIELD]);
                }
            }

            //cards:
            if ($block['type'] === 'filament-flexible-content-blocks::cards') {
                if (isset($block['data']['cards'])) {
                    foreach ($block['data']['cards'] as $card) {
                        if (! isset($card[BlockIdField::FIELD])) {
                            $card[BlockIdField::FIELD] = BlockIdField::generateBlockId();
                            foreach ($this->imageFields as $imageField) {
                                if (isset($card[$imageField]) && $card[$imageField]) {
                                    $this->updateMedia($card[$imageField], $card[BlockIdField::FIELD]);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $contentBlocks;
    }

    private function updateMedia(?string $mediaUuid, string $blockId)
    {
        if ($mediaUuid) {
            /* @var Media $media */
            $media = Media::findByUuid($mediaUuid);
            if ($media) {
                //add block id as custom property to media:
                $media->setCustomProperty('block', $blockId);
                $media->save();
            }
        }
    }
}
