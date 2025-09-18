<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data;

class CollapsibleItemData
{
    public function __construct(
        public string $title,
        public string $text,
        public bool $isOpenByDefault,
    ) {}
}
