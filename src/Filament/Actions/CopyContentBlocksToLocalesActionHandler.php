<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Actions;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockIdField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ContentBlocksField;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks;

/**
 * Re-usable implementation for Filament actions to copy the content blocks to other locales.
 */
class CopyContentBlocksToLocalesActionHandler
{
    public function handle(Model&HasMedia $record, Component $livewire, ?array $contentBlocks): void
    {
        if ($contentBlocks) {
            //check if the LocaleSwitch action is implemented:
            if (! method_exists($livewire, 'getActiveFormsLocale')) {
                Notification::make()
                    ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.error_resource_not_translatable'))
                    ->danger()
                    ->send();

                return;
            }

            if (! method_exists($record, 'setTranslation')) {
                Notification::make()
                    ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.error_model_not_translatable'))
                    ->danger()
                    ->send();

                return;
            }

            try {
                //get other locales than the current one.
                $currentLocale = $livewire->getActiveFormsLocale();
                $otherLocales = collect(FilamentFlexibleContentBlocks::getLocales())->diff([$currentLocale]);

                DB::beginTransaction();
                //copy content blocks
                foreach ($otherLocales as $otherLocale) {
                    $convertedContentBlocks = $this->convertContentBlockIdAndImages($record, $contentBlocks, $otherLocale);
                    $record->setTranslation(ContentBlocksField::FIELD, $otherLocale, $convertedContentBlocks);
                    //update form data:
                    $livewire->otherLocaleData[$otherLocale][ContentBlocksField::FIELD] = $convertedContentBlocks;
                }

                if ($otherLocales->isNotEmpty()) {
                    $record->save();
                }
                DB::commit();

                Notification::make()
                    ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.success'))
                    ->success()
                    ->send();

                //reload page to see the changes:
                redirect(request()->header('Referer'));
            } catch (\Exception $exception) {
                Log::error($exception);

                DB::rollBack();
                Notification::make()
                    ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.error', ['error' => $exception->getMessage()]))
                    ->danger()
                    ->send();
            }
        }
    }

    private function convertContentBlockIdAndImages(Model&HasMedia $record, array $contentBlocks, string $locale): array
    {
        $convertedBlocks = [];
        foreach ($contentBlocks as $block) {
            $convertedBlocks[] = $this->convertBlockIdAndCopyImagesToBlock($record, $block);
        }

        return $convertedBlocks;
    }

    private function convertBlockIdAndCopyImagesToBlock(Model&HasMedia $record, array $contentBlock): array
    {
        $dataBlock = &$contentBlock;
        if (isset($dataBlock['data'])) {
            $dataBlock = &$contentBlock['data'];
        }

        //generate new block id:
        if (isset($dataBlock[BlockIdField::FIELD]) && $dataBlock[BlockIdField::FIELD]) {
            $oldBlockId = $dataBlock[BlockIdField::FIELD];
            $newBlockId = BlockIdField::generateBlockId();
            $dataBlock[BlockIdField::FIELD] = $newBlockId;

            //copy media to new block:
            $oldBlockMedia = $record->getMedia('*', ['block' => $oldBlockId]);
            foreach($oldBlockMedia as $oldBlockMediaItem) {
                $this->copyImage($record, $oldBlockMediaItem, $newBlockId);
            }

            foreach ($dataBlock as $var => $data) {
                if (is_array($data)) {
                    $dataBlock[$var] = $this->convertBlockIdAndCopyImagesToBlock($record, $data);
                }
            }
        }

        return $contentBlock;
    }

    private function copyImage(Model&HasMedia $record, Media $oldMediaItem, ?string $blockId): string
    {
        $copiedImage = $oldMediaItem->copy($record, $oldMediaItem->collection_name, $oldMediaItem->disk);

        //set block ID:
        if ($blockId) {
            $copiedImage->setCustomProperty('block', $blockId);
            $copiedImage->save();
        }

        return $copiedImage->uuid;
    }
}
