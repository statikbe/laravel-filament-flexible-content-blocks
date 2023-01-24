<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Builder;

class ContentBlocksField extends Builder
{
    public static function create()
    {
        return Builder::make('content_blocks')
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks_lbl'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        //if no blocks are set, we can set the default blocks of the model:
        if (empty($this->childComponents)) {
            $this->blocks($this->getRecord()->getRegisteredContentBlocks());
        }
    }
}
