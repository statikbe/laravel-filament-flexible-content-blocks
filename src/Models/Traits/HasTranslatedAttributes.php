<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

    trait HasTranslatedAttributes
    {
        protected function mergeTranslatable(array $translatableAttributes): void
        {
            $this->translatable = array_merge(
                parent::getTranslatableAttributes(),
                $translatableAttributes
            );
        }
    }
