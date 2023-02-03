<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Component;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component as Livewire;

class ContentBlocksField extends Builder
{
    /**
     * @param  array<Block>  $contentBlocks
     * @return Builder
     */
    public static function create(): static
    {
        return self::make('content_blocks')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks_lbl'))
            ->childComponents(function (Model $record, Component $component, Livewire $livewire) {
                /* @var Page $livewire */

                //set the blocks based on the list of block classes configured on the resource.
                return $livewire::getResource()::getFilamentContentBlocks();
            });
    }
}
