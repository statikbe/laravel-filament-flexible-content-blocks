<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions;

use Filament\Tables\Actions\Action;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable;

class ViewAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'view';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.view_page_lbl'));

        $this->color('secondary');

        $this->icon('heroicon-s-eye');

        $this->disableForm();

        $this->url(fn (Linkable $record) => $record->getPreviewUrl())
            ->openUrlInNewTab();
    }
}
