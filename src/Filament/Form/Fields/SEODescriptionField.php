<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

    class SEODescriptionField extends DescriptionField
    {
        protected static function getFieldName(): string
        {
            return 'seo_description';
        }
    }
