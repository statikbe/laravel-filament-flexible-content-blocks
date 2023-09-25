<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Actions;

use Closure;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component as Livewire;
use Spatie\Translatable\HasTranslations;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Actions\CopyContentBlocksToLocalesActionHandler;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ContentBlocksField;
use Filament\Forms\Get;

class CopyContentBlocksToLocalesAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel();
        $this->execute(function (?Model $record, Livewire $livewire, Get $get) {
            /* @var Model&HasTranslations $record */
            return Action::make('copy_content_blocks_to_other_locales')
                ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales_lbl'))
                ->requiresConfirmation()
                ->modalHeading(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_heading'))
                ->modalDescription(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_subheading'))
                ->modalSubmitActionLabel(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_button'))
                ->modalWidth('md')
                ->icon('heroicon-o-language')
                ->action(function () use ($get, $record, $livewire) {
                    $handler = new CopyContentBlocksToLocalesActionHandler();
                    $handler->handle($record, $livewire, $get(ContentBlocksField::FIELD));
                });
        });
    }
}
