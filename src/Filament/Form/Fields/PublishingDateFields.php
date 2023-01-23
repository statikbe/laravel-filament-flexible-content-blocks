<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

    use Filament\Forms\Components\Fieldset;

    class PublishingDateFields extends Fieldset
    {
        public static function make(string $title = null): static
        {
            return parent::make($title ?? trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.publishing_title'))
                ->columns(2);
        }

        protected function setUp(): void
        {
            $this->childComponents = [
                PublishingBeginsAtField::create(),
                PublishingEndsAtField::create(),
            ];
        }
    }
