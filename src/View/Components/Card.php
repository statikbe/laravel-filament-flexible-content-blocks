<?php

    namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

    use Illuminate\View\Component;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData;

    class Card extends Component
    {
        public CardData $card;

        /**
         * @param  string|null  $title
         * @param  string|null  $description
         * @param  string|null  $image
         * @param  CallToActionData[]|null  $callToActions
         */
        public function __construct(
            ?CardData $data = null,
            ?string $title = null,
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
        }

        public function render()
        {
            return view('filament-flexible-content-blocks::components.card');
        }
    }
