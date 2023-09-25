<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks;

use Closure;
use Filament\Forms\Components\Repeater;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;

class CallToActionRepeater extends Repeater
{
    protected array|Closure|null $callToActionTypes = null;

    /* @var class-string<AbstractContentBlock>|null $blockClass */
    protected ?string $blockClass = null;

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function create(string $name, string $blockClass): static
    {
        $static = app(static::class, ['name' => $name]);
        $static->blockClass = $blockClass;

        $static->configure();

        return $static;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->schema([
            CallToActionField::make('call_to_action', $this->blockClass)
                ->types(fn () => $this->getCallToActionTypes())
                ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_lbl'))
                ->view('filament-forms::components.grid'),
        ]);
        $this->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_lbl'));
        $this->itemLabel(function (array $state): ?string {
            return $state[CallToActionField::FIELD_BUTTON_LABEL] ?? null;
        });
        $this->addActionLabel(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.add_call_to_action'));
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
