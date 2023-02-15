<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Livewire\Component as Livewire;

class ContentBlocksField extends Builder
{
    public static function create(): static
    {
        return self::make('content_blocks')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks_lbl'))
            ->childComponents(function (Livewire $livewire) {
                /** @var Page $livewire */
                //to set the blocks, filament uses the childComponents.
                //set the blocks based on the list of block classes configured on the resource.
                /** @var resource $resource */
                $resource = $livewire::getResource();

                return $resource::getModel()::getFilamentContentBlocks();
            });
    }
}
