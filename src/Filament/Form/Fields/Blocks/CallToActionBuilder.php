<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Closure;
use Filament\Forms\Components\Builder;

class CallToActionBuilder extends Builder
{
    protected array|Closure|null $callToActionTypes = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->blocks([
            Builder\Block::make('call_to_action')
                ->schema([
                    CallToActionField::make('call_to_action', self::class)
                        ->types(fn () => $this->getCallToActionTypes())
                        ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_lbl'))
                        ->view('forms::components.grid'),
                ])
                ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_lbl'))
                ->icon('heroicon-o-cursor-click'),
        ]);

        $this->disableLabel();
        $this->createItemButtonLabel(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.add_call_to_action'));
    }

    public function callToActionTypes(array|Closure|null $types): static
    {
        $this->callToActionTypes = $types;

        return $this;
    }

    public function getCallToActionTypes(): array
    {
        return $this->evaluate($this->callToActionTypes);
    }
}
