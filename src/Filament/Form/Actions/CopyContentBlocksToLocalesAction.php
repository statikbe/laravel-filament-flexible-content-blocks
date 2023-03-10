<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Actions;

use Closure;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component as Livewire;
use Spatie\Translatable\HasTranslations;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Actions\CopyContentBlocksToLocalesActionHandler;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ContentBlocksField;

class CopyContentBlocksToLocalesAction extends FormAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->disableLabel();
        $this->execute(function (?Model $record, Livewire $livewire, Closure $get) {
            /* @var Model&HasTranslations $record */
            return Action::make('copy_content_blocks_to_other_locales')
                ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales_lbl'))
                ->requiresConfirmation()
                ->modalHeading(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_heading'))
                ->modalSubheading(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_subheading'))
                ->modalButton(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_button'))
                ->modalWidth('md')
                ->icon('heroicon-o-translate')
                ->action(function () use ($get, $record, $livewire) {
                    $handler = new CopyContentBlocksToLocalesActionHandler();
                    $handler->handle($record, $livewire, $get(ContentBlocksField::FIELD));
                });
        });
    }
}
