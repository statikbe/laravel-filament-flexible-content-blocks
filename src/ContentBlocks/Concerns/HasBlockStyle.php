<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns;

use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockStyleField;

trait HasBlockStyle
{
    //we need to use a static string instead of a const, because PHP8.1 is lacking const in traits:
    public static string $DEFAULT_BLOCK_STYLE = 'default';

    public ?string $blockStyle;

    protected function setBlockStyle(array $blockData): void
    {
        $this->blockStyle = $blockData[BlockStyleField::FIELD] ?? null;
    }

    public function hasDefaultBlockStyle(): bool
    {
        return $this->blockStyle && trim($this->blockStyle) === static::$DEFAULT_BLOCK_STYLE;
    }

    public function getBlockStyleTemplateSuffix(): string
    {
        if ($this->blockStyle && ! empty(trim($this->blockStyle)) && ! $this->hasDefaultBlockStyle()) {
            return '-'.$this->blockStyle;
        } else {
            return '';
        }
    }
}
