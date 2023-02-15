<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns;

use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type\CallToActionType;

trait HasCallToAction
{
    /**
     * @return array<class-string>
     */
    public static function getCallToActionModels(): array
    {
        return config('filament-flexible-content-blocks.block_specific.'.self::class.'.call_to_action_models',
            config('filament-flexible-content-blocks.call_to_action_models', [])
        );
    }

    /**
     * @return array<string, CallToActionType>
     */
    public static function getCallToActionTypes(): array
    {
        $urlType = new CallToActionType('Url');
        $urlType->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_model_type_url'))
            ->setAsUrlType();

        $types = collect(static::getCallToActionModels())->map(fn ($item) => new CallToActionType($item))->toArray();

        return array_merge(['url' => $urlType], $types);
    }
}
