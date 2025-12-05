<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Actions;

use Filament\Actions\Action;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component as Livewire;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Actions\CopyContentBlocksToLocalesActionHandler;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ContentBlocksField;

/**
 * This action can be added as a form component.
 */
class CopyContentBlocksToLocalesAction extends Actions
{
    public static function create(): static
    {
        return static::make([
            Action::make('copy_content_blocks_to_other_locales')
                ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales_lbl'))
                ->requiresConfirmation()
                ->modalHeading(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_heading'))
                ->modalDescription(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_subheading'))
                ->modalSubmitActionLabel(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.copy_content_blocks_to_other_locales.modal_button'))
                ->modalWidth('md')
                ->icon('heroicon-o-language')
                // hide if there is only one locale available
                ->hidden(fn ($livewire) => method_exists($livewire, 'getTranslatableLocales') && count($livewire->getTranslatableLocales()) <= 1)
                ->action(function (Get $get, Model&HasMedia $record, Livewire $livewire) {
                    $handler = new CopyContentBlocksToLocalesActionHandler;
                    $handler->handle($record, $livewire, $get(ContentBlocksField::FIELD));
                }),
        ]);
    }
}
