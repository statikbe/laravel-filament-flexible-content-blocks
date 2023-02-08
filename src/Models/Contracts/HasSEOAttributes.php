<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

interface HasSEOAttributes
{
    /**
     * Returns the SEO title
     *
     * @return string|null
     */
    public function getSEOTitle(): ?string;

    /**
     * Returns the SEO description
     *
     * @return string|null
     */
    public function getSEODescription(): ?string;

    /**
     * Add an SEO image to the model.
     *
     * @param  string  $imagePath
     * @return void
     */
    public function addSEOImage(string $imagePath): void;

    /**
     * Returns the name of the media library conversion of the SEO image
     *
     * @return string
     */
    public function getSEOImageConversionName(): string;

    /**
     * Get the name of the media library collection of the SEO image
     *
     * @return string
     */
    public function getSEOImageCollection(): string;

    /**
     * Get URL of SEO image
     *
     * @param  string|null  $conversion
     * @return string|null
     */
    public function getSEOImageUrl(string $conversion = null): ?string;
}
