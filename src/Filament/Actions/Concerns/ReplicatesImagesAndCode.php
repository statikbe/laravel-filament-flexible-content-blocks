<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Actions\Concerns;

use App\Filament\Resources\PageResource;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\CodeField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\TitleField;

/**
 * @mixin \Filament\Tables\Actions\ReplicateAction
 * @mixin \Filament\Actions\ReplicateAction
 *
 * @property Model&HasMedia&HasTranslations $replica
 */
trait ReplicatesImagesAndCode
{
    protected Model $originalRecord;

    public function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-document-duplicate')
            ->beforeReplicaSaved(function (Model&HasMedia $record) {
                $this->originalRecord = $record;

                if (method_exists($this->replica, 'hasAttribute')) {
                    // clear the code field because it should be unique:
                    if ($this->replica->hasAttribute(CodeField::FIELD)) {
                        $this->replica->setAttribute(CodeField::FIELD, null);
                    }

                    // add '(copy)' postfix to title
                    if ($this->replica->hasAttribute(TitleField::getFieldName())) {
                        if ($this->replica instanceof HasTranslations) {
                            $translations = $this->replica->getTranslations(TitleField::getFieldName());
                            foreach ($translations as $locale => $title) {
                                if (! empty(trim($title))) {
                                    $translations[$locale] = $title.' (copy)';
                                }
                            }
                            $this->replica->setTranslations(TitleField::getFieldName(), $translations);
                        }
                    }
                }
            })
            ->after(fn ($record) => $this->copyImagesToNewRecord())
            ->successRedirectUrl(fn () => PageResource::getUrl('edit', ['record' => $this->getReplica()]));
    }

    public function copyImagesToNewRecord(): void
    {
        if (! $this->originalRecord instanceof HasMedia || ! $this->replica instanceof HasMedia) {
            return;
        }

        $this->originalRecord->getMedia('*')
            ->each(function (Media $mediaItem) {
                if ($this->replica instanceof HasMedia) {
                    $mediaItem->copy($this->replica, $mediaItem->collection_name, $mediaItem->disk);
                }
            });
    }
}
