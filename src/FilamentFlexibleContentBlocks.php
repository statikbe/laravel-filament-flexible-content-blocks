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
     *
     * @param  array  $locales
     */
    public static function setLocales(array $locales)
    {
        static::$locales = $locales;
    }

    /**
     * Get the supported locales array for translated content blocks.
     *
     * @return array
     */
    public static function getLocales(): array
    {
        return static::$locales;
    }
}
