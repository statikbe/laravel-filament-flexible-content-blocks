<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Illuminate\Database\Eloquent\Model;

class ContentBlocksField extends Builder
{
    /**
     * @param  array<Block>  $contentBlocks
     * @return Builder
     */
    public static function create(array $contentBlocks): static
    {
        return self::make('content_blocks')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks_lbl'))
            ->childComponents(function (Model $record) {
                dump('inside content block field');
                dd($record);
            })
            ->blocks($contentBlocks);
    }
}
