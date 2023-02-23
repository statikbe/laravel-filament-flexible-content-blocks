<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns;

use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockStyleField;

trait HasBlockStyle
{
    public ?string $blockStyle;

    protected function setBlockStyle(array $blockData): void
    {
        $this->blockStyle = $blockData[BlockStyleField::FIELD] ?? null;
    }

    public function getBlockStyleTemplateSuffix(): string
    {
        if ($this->blockStyle && ! empty($this->blockStyle)) {
            return '-'.$this->blockStyle;
        } else {
            return '';
        }
    }
}
