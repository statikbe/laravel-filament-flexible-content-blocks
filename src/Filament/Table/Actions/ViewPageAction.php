<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions;

use Filament\Actions\Action;
use Filament\Resources\Pages\Page as FilamentPage;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable;

class ViewPageAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'view_page';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.view_page_lbl'))
            ->color('gray')
            ->icon('heroicon-s-eye')
            ->url(function (Linkable $record, FilamentPage $livewire): string {
                $locale = app()->getLocale();

                return $record->getPreviewUrl($locale);
            })
            ->openUrlInNewTab();
    }
}
