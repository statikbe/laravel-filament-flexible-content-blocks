<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Illuminate\Support\Str;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocksServiceProvider;

/**
 * Adds extra convenience functionality for content blocks to deal with package specific logic, such as
 * package related translations and naming.
 *
 * @note Blocks implemented outside of the filament-flexible-content-blocks package should extend AbstractContentBlock!
 */
abstract class AbstractFilamentFlexibleContentBlock extends AbstractContentBlock
{
    /**
     * Returns the last part of the name of the block. For the filament flexible content blocks package we prefix each
     * block name with the package name.
     */
    abstract public static function getNameSuffix(): string;

    /**
     * Will be used to label each block based on their content
     */
    public static function getContentSummary(array $state): ?string
    {
        return '';
    }

    public static function getName(): string
    {
        $nameSuffix = static::getNameSuffix();

        return sprintf('%s::%s', FilamentFlexibleContentBlocksServiceProvider::$name, $nameSuffix);
    }

    public static function getLabel(): string
    {
        $name = static::getNameSuffix();

        return trans("filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.{$name}.label");
    }

    public static function getContextualLabel(?array $state): ?string
    {
        $staticLabel = static::getLabel();
        $contentSummary = ($state === null) ? '' : static::getContentSummary($state);

        return ($contentSummary !== null && $contentSummary !== '')
            ? $staticLabel.' - '.Str::limit($contentSummary, 40, '...')
            : $staticLabel;
    }

    public static function getFieldLabel(string $field): string
    {
        $name = static::getNameSuffix();

        return trans("filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.{$name}.{$field}");
    }

    public function render()
    {
        $themePrefix = FilamentFlexibleBlocksConfig::getViewThemePrefix();

        $templateSuffix = static::getNameSuffix();
        if (method_exists($this, 'getBlockStyleTemplateSuffix')) {
            $blockStyle = $this->getBlockStyleTemplateSuffix();
            if (! empty($blockStyle)) {
                $templateSuffix .= $blockStyle;
            }
        }

        return view("filament-flexible-content-blocks::content-blocks.{$themePrefix}{$templateSuffix}");
    }

    public function getSearchableContent(): array
    {
        return [];
    }
}
