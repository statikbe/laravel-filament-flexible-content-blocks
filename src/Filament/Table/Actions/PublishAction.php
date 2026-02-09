<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions;

use Carbon\Carbon;
use Exception;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasPageAttributes;

class PublishAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'publish';
    }

    public function setUp(): void
    {
        $this->action(function (Model&HasPageAttributes $record) {
            try {
                if (! $record->isPublished()) {
                    $record->setAttribute('publishing_begins_at', Carbon::now());
                    if ($record->wasUnpublished()) {
                        $record->setAttribute('publishing_ends_at', null);
                    }
                    $record->save();

                    Notification::make()
                        ->success()
                        ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.publish_notification_success_title'))
                        ->body(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.publish_notification_success_msg'))
                        ->send();
                } else {
                    $record->setAttribute('publishing_ends_at', Carbon::now());
                    $record->save();

                    Notification::make()
                        ->success()
                        ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.unpublish_notification_success_title'))
                        ->body(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.unpublish_notification_success_msg'))
                        ->send();
                }
            } catch (Exception $ex) {
                Log::error($ex);
                Notification::make()
                    ->danger()
                    ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.notification_error_title'))
                    ->body($ex->getMessage())
                    ->send();
            }

            return response();
        });
        $this->label(function (Model&HasPageAttributes $record) {
            $isPublished = $record->isPublished();

            return $isPublished
                ? trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.unpublish_lbl')
                : trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.publish_lbl');
        });
        $this->icon(function (Model&HasPageAttributes $record) {
            $isPublished = $record->isPublished();

            return $isPublished ? Heroicon::EyeSlash : Heroicon::GlobeAlt;
        });
        $this->color('gray');
    }
}
