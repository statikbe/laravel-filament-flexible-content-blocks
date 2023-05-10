<?php

namespace Statikbe\FilamentFlexibleContentBlocks;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Statikbe\FilamentFlexibleContentBlocks\Replacer\TextParameterReplacer;

class FilamentFlexibleContentBlocks
{
    /**
     * The supported locales array for translated content blocks.
     *
     * @var array
     */
    public static $locales;

    private static $replacerParameters;

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

    /**
     * Replaces parameters in text content. Parameters can be configured by implementing a Statikbe\FilamentFlexibleContentBlocks\Replacer\TextParameterReplacer
     * and configuring it as `text_parameter_replacer` in the config file.
     */
    public static function replaceParameters(?string $content): ?string
    {
        if (! $content || empty(trim($content)) || empty(static::getReplacerParameters())) {
            return $content;
        }

        //the code below is based on the Illuminate\Translation\Translator->makeReplacements implementation:
        $shouldReplace = [];

        foreach (static::getReplacerParameters() as $key => $value) {
            $shouldReplace[':'.Str::ucfirst($key ?? '')] = Str::ucfirst($value ?? '');
            $shouldReplace[':'.Str::upper($key ?? '')] = Str::upper($value ?? '');
            $shouldReplace[':'.$key] = $value;
        }

        return strtr($content, $shouldReplace);
    }

    protected static function getReplacerParameters(): array
    {
        if (isset(static::$replacerParameters) && static::$replacerParameters) {
            return static::$replacerParameters;
        }

        $replacerClass = FilamentFlexibleBlocksConfig::getTextParameterReplacer();
        if (! $replacerClass) {
            return [];
        }

        /* @var TextParameterReplacer $replacer */
        $replacer = App::make($replacerClass);
        static::$replacerParameters = $replacer->getParameters();

        return static::$replacerParameters;
    }
}
