<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Actions\CopyContentBlocksToLocalesActionHandler;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ContentBlocksField;

class CopyContentBlocksToLocalesAction extends Actions
{
    public static function create(): static {
        return static::make([
            Action::make('copy_content_blocks_to_other_locales')
                ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales_lbl'))
                ->requiresConfirmation()
                ->modalHeading(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_heading'))
                ->modalDescription(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_subheading'))
                ->modalSubmitActionLabel(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_button'))
                ->modalWidth('md')
                ->icon('heroicon-o-language')
                ->action(function ($get, $record, $livewire) {
                    $handler = new CopyContentBlocksToLocalesActionHandler();
                    $handler->handle($record, $livewire, $get(ContentBlocksField::FIELD));
                }),
        ]);
    }
}
