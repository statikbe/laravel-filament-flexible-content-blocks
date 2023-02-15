<?php

namespace Statikbe\FilamentFlexibleContentBlocks;

class FilamentFlexibleContentBlocks
{
    /**
     * The supported locales array for translated content blocks.
     *
     * @var array
     */
    public static $locales;

    /**
     * Set the supported locales array for translated content blocks.
     */
    public static function setLocales(array $locales)
    {
        static::$locales = $locales;
    }

    /**
     * Get the supported locales array for translated content blocks.
     */
    public static function getLocales(): array
    {
        return static::$locales;
    }
}
