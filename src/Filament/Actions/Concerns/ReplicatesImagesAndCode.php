<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Actions\Concerns;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\CodeField;

/**
 * @mixin \Filament\Tables\Actions\ReplicateAction
 * @mixin \Filament\Actions\ReplicateAction
 */
trait ReplicatesImagesAndCode
{
    protected Model $originalRecord;

    public function setUp(): void
    {
        parent::setUp();

        $this->beforeReplicaSaved(function (Model&HasMedia $record) {
            $this->originalRecord = $record;

            if ($this->replica->hasAttribute(CodeField::FIELD)) {
                $this->replica->setAttribute(CodeField::FIELD, null);
            }
        })
            ->after(fn ($record) => $this->copyImagesToNewRecord());
    }

    public function copyImagesToNewRecord(): void
    {
        /** @var HasMedia $replica */
        $replica = $this->replica;

        $this->originalRecord->getMedia('*')
            ->each(function (Media $mediaItem) use ($replica) {
                $mediaItem->copy($replica, $mediaItem->collection_name, $mediaItem->disk);
            });
    }
}
