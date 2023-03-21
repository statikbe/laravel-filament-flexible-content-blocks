<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns;

use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type\CallToActionType;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

trait HasCallToAction
{
    /**
     * @return array<string, CallToActionType>
     */
    public static function getCallToActionTypes(): array
    {
        $urlType = new CallToActionType('Url');
        $urlType->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_model_type_url'))
            ->setAsUrlType();

        $routeType = new CallToActionType('Route');
        $routeType->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_model_type_route'))
            ->setAsRouteType();

        $types = collect(FilamentFlexibleBlocksConfig::getCallToActionModels(static::class))->map(fn ($item) => new CallToActionType($item))->toArray();

        return array_merge(['url' => $urlType, 'route' => $routeType], $types);
    }

    /**
     * @param  array{call_to_action: array}  $blockData
     */
    public function createSingleCallToAction(array $blockData): ?CallToActionData
    {
        if (! empty($blockData['call_to_action'])) {
            return CallToActionData::create($blockData['call_to_action'][0], CallToActionField::getButtonStyleClasses(static::class));
        } else {
            return null;
        }
    }

    /**
     * @param  array{call_to_action: array}  $blockData
     * @return CallToActionData[]
     */
    public function createMultipleCallToActions(array $blockData): array
    {
        if (! empty($blockData['call_to_action'])) {
            $data = [];
            foreach ($blockData['call_to_action'] as $callToAction) {
                $data[] = CallToActionData::create($callToAction, CallToActionField::getButtonStyleClasses(static::class));
            }

            return $data;
        } else {
            return [];
        }
    }
}
