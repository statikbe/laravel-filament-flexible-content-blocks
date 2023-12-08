<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;

interface HasOverviewAttributes
{
    /**
     * Returns the overview title
     */
    public function getOverviewTitle(): ?string;

    /**
     * Returns the overview description
     */
    public function getOverviewDescription(): ?string;

    /**
     * Add overview image to the model
     */
    public function addOverviewImage(string $imagePath): void;

    /**
     * Returns the media library conversion of the overview image
     */
    public function getOverviewImageConversionName(): string;

    /**
     * Returns the media library collection of the overview image
     */
    public function getOverviewImageCollection(): string;

    /**
     * Returns the overview image url
     */
    public function getOverviewImageUrl(?string $conversion = null): ?string;

    /**
     * Get the html view of the overview image
     */
    public function getOverviewImageMedia(?string $conversion = null, array $attributes = []): ?HtmlableMedia;
}
