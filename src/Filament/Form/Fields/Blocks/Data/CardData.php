<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data;

use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CardsBlock;

class CardData
{
    /**
     * @param  array<CallToActionData>|null  $callToActions
     */
    public function __construct(
            public ?string $title,
            public ?string $text,
            public ?string $imageId,
            public ?array $callToActions,
            public CardsBlock $block,
        ) {
    }

    /**
     * @param  array {card_title: string, card_text: string, card_image: string, card_call_to_action: array<array{cta_model: string, entry_id: ?string, url: ?string, button_style: ?string, button_label: ?string, button_open_new_window: ?boolean}> }  $cardBlockData
     */
    public static function create(array $cardBlockData, array $buttonStyleClasses, CardsBlock $block): self
    {
        $callToActions = [];
        if (! empty($cardBlockData['card_call_to_action'])) {
            foreach ($cardBlockData['card_call_to_action'] as $callToAction) {
                $callToActions[] = CallToActionData::create($callToAction, $buttonStyleClasses);
            }
        }

        return new self(
            $cardBlockData['title'] ?? null,
            $cardBlockData['text'] ?? null,
            $cardBlockData['image'] ?? null,
            $callToActions,
            $block
        );
    }
}
