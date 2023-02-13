<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;

interface HasOverviewAttributes
{
    /**
     * Returns the overview title
     *
     * @return string|null
     */
    public function getOverviewTitle(): ?string;

    /**
     * Returns the overview description
     *
     * @return string|null
     */
    public function getOverviewDescription(): ?string;

    /**
     * Add overview image to the model
     *
     * @param  string  $imagePath
     * @return void
     */
    public function addOverviewImage(string $imagePath): void;

    /**
     * Returns the media library conversion of the overview image
     *
     * @return string
     */
    public function getOverviewImageConversionName(): string;

    /**
     * Returns the media library collection of the overview image
     *
     * @return string
     */
    public function getOverviewImageCollection(): string;

    /**
     * Returns the overview image url
     *
     * @param  string|null  $conversion
     * @return string|null
     */
    public function getOverviewImageUrl(string $conversion = null): ?string;

    /**
     * Get the html view of the overview image
     *
     * @param  array  $attributes
     * @return HtmlableMedia|null
     */
    public function getOverviewImageMedia(array $attributes = []): ?HtmlableMedia;
}
