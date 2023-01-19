<?php

    namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions;

    use App\Filament\Resources\ShippingOrderResource;
    use App\Models\ShippingOrder;
    use Carbon\Carbon;
    use Filament\Facades\Filament;
    use Filament\Notifications\Notification;
    use Filament\Pages\Actions\Action;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Log;
    use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasPageAttributes;

    class PublishAction extends Action {
    public function setUp(): void {
        $this->action(function () {
            try {
                /** @var HasPageAttributes */
                $page = $this->getRecord();
                if(function_exists('isPublished') && !$page->isPublished()){
                    $page->publishing_begins_at = Carbon::now();
                    if(function_exists('wasUnpublished') && $page->wasUnpublished()){
                        $page->publishing_ends_at = null;
                    }
                    $page->save();
                }
                Notification::make()
                    ->success()
                    ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.notification_success_title'))
                    ->body(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.table_action.publish.notification_success_msg'))
                    ->send();
            }
            catch(\Exception $ex) {
                Log::error($ex);
                Notification::make()->danger()->title('Error')->body($ex->getMessage())->send();
            }
            return response();
        });
        $this->label(function(){
            //callback function because record might not be set yet.
            if($this->getRecord()->hasRetourOrder()){
                return __('merchants.shipping_order.go_to_retour_action');
            }
            elseif($this->getRecord()->isRetourOrder()){
                return __('merchants.shipping_order.go_to_original_action');
            }
            else{
                return __('merchants.shipping_order.create_retour_action');
            }
        });
        $this->icon('heroicon-o-chevron-double-left');
        $this->color('secondary');
        $this->hidden(function(){
            return !$this->getRecord()->hasLabels() && !$this->getRecord()->isRetourOrder();
        });
    }

}
