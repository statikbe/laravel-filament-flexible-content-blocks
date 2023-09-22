<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Resource\Pages\Actions;

use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Actions\CopyContentBlocksToLocalesActionHandler;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

class CopyContentBlocksToLocalesAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'copy_content_blocks_to_other_locales_page_action';
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales_lbl'));

        $this->modalHeading(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_heading'));
        $this->modalSubheading(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_subheading'));
        $this->modalButton(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_button'));

        $this->button();

        $this->icon('heroicon-o-language');

        $this->action(function () {
            /** @var EditRecord $page * */
            $page = $this->livewire;
            /* @var Model&HasContentBlocks $record */
            $record = $page->getRecord();
            $handler = new CopyContentBlocksToLocalesActionHandler();
            $handler->handle($record, $this->livewire, $record->content_blocks);
        });
    }
}
