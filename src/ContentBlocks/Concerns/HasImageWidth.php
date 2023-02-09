<?php

    namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns;

    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\ImageWidthField;

    trait HasImageWidth {
        public function getImageWidth(?string $imageWidthType): ?string {
            return ImageWidthField::getImageWidthClass($imageWidthType);
        }
    }
