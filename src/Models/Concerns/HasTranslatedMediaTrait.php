<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Concerns;

/**
 * @property array $translatableMediaCollections
 */
trait HasTranslatedMediaTrait
{
    /**
     * @var string[]
     */
    public $translatableMediaCollections = [];

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
        })
            ->mapWithKeys(function ($item, string $key) {
                //make sure the uuids are also available as key. the keys are used to delete unused media files.
                return [$key => $item->combine($item)];
            })
            ->toArray();
    }
}
