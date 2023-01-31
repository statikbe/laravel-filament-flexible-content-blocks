<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Builder;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Resource\HasContentBlocks;

class ContentBlocksField extends Builder
{
    public static function create(HasContentBlocks $resource)
    {
        return Builder::make('content_blocks')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks_lbl'))
            ->blocks($resource::getContentBlocks());
    }
}
