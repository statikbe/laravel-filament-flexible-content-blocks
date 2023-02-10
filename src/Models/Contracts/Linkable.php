<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Contracts;

interface Linkable
{
    /**
     * Returns the URL of the view route of this model. This is used to create links from the model instance.
     *
     * @return string
     */
    public function getViewUrl(): string;
}
