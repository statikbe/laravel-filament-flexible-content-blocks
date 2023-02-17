<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

use Illuminate\View\Component;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData;

class Card extends Component
{
    public CardData $card;

    private ?string $titleUrl;

    /**
     * @param  CallToActionData[]|null  $callToActions
     */
    public function __construct(
            ?CardData $data = null,
            ?string $title = null,
            ?string $titleUrl = null,
            ?string $text = null,
            ?array $callToActions = [],
            ?string $image = null,
            ?string $imageUrl = null,
        ) {
        if ($data) {
            $this->card = $data;
        } else {
            $this->card = new CardData($title, $text, $callToActions, null, $imageUrl, $image);
        }

        $this->titleUrl = $titleUrl;
    }

    public function getTitleUrl(): ?string
    {
        if (! $this->titleUrl) {
            $this->titleUrl = $this->card->callToActions[0]->url ?? null;
        }

        return $this->titleUrl;
    }

    public function render()
    {
        return view('filament-flexible-content-blocks::components.card');
    }
}
