<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions;

use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasPageAttributes;

class PublishAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'publish';
    }

    public function setUp(): void
    {
        $this->action(function () {
            try {
                $page = $this->getRecord();
                if (method_exists($page, 'isPublished') && ! $page->isPublished()) {
                    $page->publishing_begins_at = Carbon::now();
                    if (method_exists($page, 'wasUnpublished') && $page->wasUnpublished()) {
                        $page->publishing_ends_at = null;
                    }
                    $page->save();

                    Notification::make()
                        ->success()
                        ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.publish_notification_success_title'))
                        ->body(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.publish_notification_success_msg'))
                        ->send();
                } else {
                    $page->publishing_ends_at = Carbon::now();
                    $page->save();

                    Notification::make()
                        ->success()
                        ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.unpublish_notification_success_title'))
                        ->body(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.unpublish_notification_success_msg'))
                        ->send();
                }
            } catch(\Exception $ex) {
                Log::error($ex);
                Notification::make()
                    ->danger()
                    ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.notification_error_title'))
                    ->body($ex->getMessage())
                    ->send();
            }

            return response();
        });
        $this->label(function () {
            /* @var Model|HasPageAttributes $page */
            $page = $this->getRecord();
            if (method_exists($page, 'isPublished') && $page->isPublished()) {
                return trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.unpublish_lbl');
            } else {
                return trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.publish_lbl');
            }
        });
        $this->icon(function () {
            /** @var Model|HasPageAttributes $page */
            $page = $this->getRecord();
            if (method_exists($page, 'isPublished') && $page->isPublished()) {
                return 'heroicon-o-eye-off';
            } else {
                return 'heroicon-o-eye';
            }
        });
        $this->color('secondary');
    }
}
