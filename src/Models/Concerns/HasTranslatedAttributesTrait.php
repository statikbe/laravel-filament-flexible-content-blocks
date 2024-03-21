<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

use Spatie\Translatable\HasTranslations;

/**
 * @property array $translatable
 * @property array $translatableMediaCollections
 */
trait HasTranslatedAttributesTrait
{
    use HasTranslations;

    /**
     * @var string[]
     */
    public $translatable = [];

    /**
     * @var string[]
     */
    public $translatableMediaCollections = [];

    protected function mergeTranslatable(array $translatableAttributes): void
    {
        $this->translatable = array_merge(
            $this->getTranslatableAttributes(),
            $translatableAttributes
        );
    }

    public function mergeTranslatableMediaCollection(array $translatableMediaCollections): void
    {
        $this->translatableMediaCollections = array_merge(
            $this->getTranslatableMediaCollections(),
            $translatableMediaCollections
        );
    }

    /**
     * @return string[]
     */
    public function getTranslatableMediaCollections(): array
    {
        return $this->translatableMediaCollections;
    }

    /**
     * Retrieves the media UUIDs per media collection. Returns an array with the media collection as key and a list of
     * media UUIDs as values.
     *
     * @return array<string, array<string>>
     */
    public function getTranslatableMediaUuidsPerMediaCollection(?string $locale = null): array
    {
        if (empty($this->getTranslatableMediaCollections())) {
            return [];
        }

        if (! $locale) {
            $locale = app()->getLocale();
        }

        $translatableMedia = $this->media()
            ->whereIn('collection_name', $this->getTranslatableMediaCollections())
            ->where('custom_properties->locale', $locale)
            ->select('collection_name', 'uuid')
            ->get();

        return $translatableMedia->mapToGroups(function ($item, int $key) {
            return [$item->collection_name => $item->uuid];
        })->toArray();
    }
}
