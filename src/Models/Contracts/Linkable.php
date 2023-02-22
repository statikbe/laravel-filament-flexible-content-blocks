<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

interface Linkable
{
    /**
     * Returns the URL of the view route of this model. This is used to create links from the model instance.
     */
    public function getViewUrl(?string $locale = null): string;

    /**
     * Returns the URL of the view route of this model, that is used to preview the page. This url should show the page also if it is not yet published, or other restriction are set.
     * Tip: only show the page for logged in roles, or maybe by using signed urls for unpublished pages.
     */
    public function getPreviewUrl(?string $locale = null): string;
}
