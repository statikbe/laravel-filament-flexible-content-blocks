<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

interface HasSEOAttributes
{
    /**
     * Returns the SEO title
     */
    public function getSEOTitle(): ?string;

    /**
     * Returns the SEO description
     */
    public function getSEODescription(): ?string;

    /**
     * Add an SEO image to the model.
     */
    public function addSEOImage(string $imagePath): void;

    /**
     * Returns the name of the media library conversion of the SEO image
     */
    public function getSEOImageConversionName(): string;

    /**
     * Get the name of the media library collection of the SEO image
     */
    public function getSEOImageCollection(): string;

    /**
     * Get URL of SEO image
     */
    public function getSEOImageUrl(?string $conversion = null): ?string;
}
