<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

    use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;

    /**
     * @property ?string $hero_image_title
     * @property ?string $hero_image_copyright
     */
    interface HasHeroImageAttributes
    {
        /**
         * Adds a hero image to the media library collection
         *
         * @param  string  $imagePath
         * @return void
         */
        public function addHeroImage(string $imagePath): void;

        /**
         * Returns the name of the media library conversion of the hero image
         *
         * @return string
         */
        public function getHeroImageConversionName(): string;

        /**
         * Returns media library collection of hero image
         *
         * @return string
         */
        public function getHeroImageCollection(): string;

        /**
         * Returns the url of the hero image
         *
         * @param  string|null  $conversion
         * @return string|null
         */
        public function getHeroImageUrl(string $conversion = null): ?string;

        /**
         * Returns the rendered html of the hero image
         *
         * @param  array  $attributes
         * @return HtmlableMedia|null
         */
        public function getHeroImageMedia(array $attributes = []): ?HtmlableMedia;
    }
