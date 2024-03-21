<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Actions;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockIdField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ContentBlocksField;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks;

/**
 * Re-usable implementation for Filament actions to copy the content blocks to other locales.
 */
class CopyContentBlocksToLocalesActionHandler
{
    private array $imageFields = ['image'];
    private array $fileUploadFields = [];

    public function handle(Model $record, Component $livewire, ?array $contentBlocks): void
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

                //copy content blocks
                foreach ($otherLocales as $otherLocale) {
                    $convertedContentBlocks = $this->convertContentBlockIsAndImages($record, $contentBlocks, $otherLocale);
                    $record->setTranslation(ContentBlocksField::FIELD, $otherLocale, $convertedContentBlocks);
                    //update form data:
                    $livewire->otherLocaleData[$otherLocale][ContentBlocksField::FIELD] = $this->transformToFileUploadFormData($convertedContentBlocks);
                }

                if ($otherLocales->isNotEmpty()) {
                    $record->save();
                }

                Notification::make()
                    ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.success'))
                    ->success()
                    ->send();

                //reload page to see the changes:
                redirect(request()->header('Referer'));
            } catch (\Exception $exception) {
                Log::error($exception);

                Notification::make()
                    ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.error', ['error' => $exception->getMessage()]))
                    ->danger()
                    ->send();
            }
        }
    }

    private function convertContentBlockIsAndImages(Model $record, array $contentBlocks, string $locale): array {
        $convertedBlocks = [];
        foreach($contentBlocks as $block){
            $block = $this->convertBlockId($block);

            $convertedBlocks[] = $this->copyImagesToBlock($record, $block);
        }

        return $convertedBlocks;
    }

    /**
     * Add extra image fields to convert.
     * Useful if you have custom blocks with images that use other field names than `image`.
     * @param array<string> $extraImageFields
     * @return void
     */
    public function addImageFields(array $extraImageFields): void {
        $this->imageFields = array_merge($this->imageFields, $extraImageFields);
    }

    /**
     * Add file upload field names used in the custom blocks to be able to transform the localized form data.
     * @param array $fileUploadFields
     * @return void
     */
    public function addFileUploadField(array $fileUploadFields): void {
        $this->fileUploadFields = array_merge($this->fileUploadFields, $fileUploadFields);
    }

    private function convertBlockId(array $contentBlock): array {
        $dataBlock = &$contentBlock;
        if(isset($dataBlock['data'])){
            $dataBlock = &$contentBlock['data'];
        }

        //generate new block id:
        if(isset($dataBlock['block_id']) && $dataBlock['block_id']){
            $dataBlock['block_id'] = BlockIdField::generateBlockId();
        }

        //handle all block IDs in deeper data structures, e.g. cards.
        foreach($dataBlock as $var => $data){
            if(is_array($data)){
                $dataBlock[$var] = $this->convertBlockId($data);
            }
        }

        return $contentBlock;
    }

    private function copyImagesToBlock(Model $record, array $contentBlock): array {
        $dataBlock = &$contentBlock;
        if(isset($dataBlock['data'])){
            $dataBlock = &$contentBlock['data'];
        }

        foreach($this->imageFields as $imageField){
            if(isset($dataBlock[$imageField])){
                $blockId = $dataBlock[BlockIdField::FIELD] ?? null;
                $dataBlock[$imageField] = $this->copyImage($record, $dataBlock[$imageField], $blockId);
            }
        }

        foreach($dataBlock as $var => $data){
            if(is_array($data)){
                $blockId = $dataBlock[BlockIdField::FIELD] ?? null;
                $dataBlock[$var] = $this->copyImagesToBlock($record, $data, $blockId);
            }
        }

        return $contentBlock;
    }

    private function copyImage(Model $record, string $imageUuid, ?string $blockId): string {
        $image = Media::findByUuid($imageUuid);

        $copiedImage = $image->copy($record, $image->collection_name, $image->disk);

        //set block ID:
        if($blockId) {
            $copiedImage->setCustomProperty('block', $blockId);
            $copiedImage->save();
        }

        return $copiedImage->uuid;
    }

    private function transformToFileUploadFormData(array $contentBlocks): array {
        foreach($contentBlocks as &$block){
            $this->transformBlockToFileUploadFormData($block);
        }

        return $contentBlocks;
    }

    private function transformBlockToFileUploadFormData(array &$contentBlock): array {
        $dataBlock = &$contentBlock;
        if(isset($dataBlock['data'])){
            $dataBlock = &$contentBlock['data'];
        }

        $fields = array_unique(array_merge($this->imageFields, $this->fileUploadFields));

        foreach($fields as $fileField){
            if(isset($dataBlock[$fileField]) && !is_array($dataBlock[$fileField])){
                //put file fields in an array:
                $dataBlock[$fileField] = [$dataBlock[$fileField] => $dataBlock[$fileField]];
            }
        }

        foreach($dataBlock as $var => $data){
            if(is_array($data) && !in_array($var, $fields)){
                $dataBlock[$var] = $this->transformBlockToFileUploadFormData($data);
            }
        }

        return $contentBlock;
    }
}
