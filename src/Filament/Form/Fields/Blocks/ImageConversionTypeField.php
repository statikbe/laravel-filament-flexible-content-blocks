<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

    use Filament\Forms\Components\Select;
    use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;

    class ImageConversionTypeField extends Select
    {
        const FIELD = 'image_conversion';

        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         */
        public static function create(string $blockClass, bool $required = true): static
        {
            return static::make(static::FIELD)
                ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.image_conversion_type_lbl'))
                ->options($blockClass::getImageConversionTypeOptions())
                ->default($blockClass::getImageConversionTypeDefault())
                ->disablePlaceholderSelection()
                ->required($required);
        }
    }
