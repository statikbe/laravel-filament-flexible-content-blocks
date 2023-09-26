<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Livewire\Component as Livewire;

class ContentBlocksField extends Builder
{
    const FIELD = 'content_blocks';

    //cache of instantiated blocks:
    protected array|null $contentBlocks;

    public static function create(): static
    {
        return static::make(static::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks_lbl'))
            ->addActionLabel(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks_add_lbl'))
            ->addBetweenActionLabel(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks_add_lbl'))
            ->blocks(function (Livewire $livewire, ContentBlocksField $component) {
                //this function is called very often, therefore we cache the results here.
                //caching on model level causes weird behaviour with livewire entangle because the block components are not refreshed each time the builder is recreated.
                if(!isset($component->contentBlocks)){
                    /** @var Page $livewire */
                    //to set the blocks, filament uses the childComponents.
                    //set the blocks based on the list of block classes configured on the resource.
                    $resource = $livewire::getResource();

                    $component->contentBlocks = array_values($resource::getModel()::getFilamentContentBlocks());
                }

                return $component->contentBlocks;
            });
    }

    /**
     * Overwritten function because there is a bug in Filament or Livewire with Builders. It appears to be in the form fill()
     * in the fillStateWithNull function a new empty block is added to the first translation with the same livewire UUID
     * as the block in the other translation.
     * {@inheritDoc}
     */
    public function getChildComponentContainers(bool $withHidden = false): array
    {
        return collect($this->getState())
            ->filter(function (array $itemData): bool {
                //extra condition to make sure $itemData has a type:
                return array_key_exists('type', $itemData) && $this->hasBlock($itemData['type']);
            })
            ->map(
                fn (array $itemData, $itemIndex): ComponentContainer => $this
                    ->getBlock($itemData['type'])
                    ->getChildComponentContainer()
                    ->statePath("{$itemIndex}.data")
                    ->inlineLabel(false)
                    ->getClone(),
            )
            ->all();
    }
}
