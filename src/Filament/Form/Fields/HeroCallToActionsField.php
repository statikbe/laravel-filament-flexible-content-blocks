<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasCallToAction;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionRepeater;

class HeroCallToActionsField extends CallToActionRepeater
{
    use HasCallToAction;

    const FIELD = 'hero_call_to_actions';

    /**
     * Use `createField()` instead of `create()`. The latter will create an unconfigured call-to-action repeater field.
     * `create()` cannot be re-used as it has args that are not needed.
     */
    public static function createField(): static
    {
        return parent::create(static::FIELD, static::class)
            ->callToActionTypes(static::getCallToActionTypes())
            ->minItems(0);
    }
}
