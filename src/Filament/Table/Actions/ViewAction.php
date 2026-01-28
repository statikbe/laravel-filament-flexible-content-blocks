<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions;

use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Support\Icons\Heroicon;
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

        $this->color('gray');

        $this->icon(Heroicon::Eye);

        $this->disabledSchema();

        $this->url(function (Linkable $record, Page $livewire): string {
            $locale = app()->getLocale();
            if (method_exists($livewire, 'getActiveTableLocale')) {
                $locale = $livewire->getActiveTableLocale();
            }

            return $record->getPreviewUrl($locale);
        })
            ->openUrlInNewTab();
    }
}
