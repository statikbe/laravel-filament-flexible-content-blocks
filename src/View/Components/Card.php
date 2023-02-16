<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

    use Illuminate\View\Component;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData;

    class Card extends Component
    {
        public ?string $titleUrl;

        /**
         * @param  string|null  $title
         * @param  string|null  $description
         * @param  string|null  $image
         * @param  CallToActionData[]|null  $callToActions
         */
        public function __construct(
            public ?string $title = null,
            public ?string $description = null,
            public ?string $image = null,
            public ?array $callToActions = [],
        ) {
            if ($this->callToActions && ! empty($this->callToActions)) {
                $this->titleUrl = $this->callToActions[0]->url;
            }
        }

        public function render()
        {
            return view('filament-flexible-content-blocks::components.card');
        }
    }
