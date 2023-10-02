<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data;

use Statikbe\FilamentFlexibleContentBlocks\Exceptions\CallToActionNotDefinedException;
use Statikbe\FilamentFlexibleContentBlocks\Exceptions\LinkableModelNotFoundException;

class CardData
{
    /**
     * @param  array<CallToActionData>|null  $callToActions
     */
    public function __construct(
        public ?string $title,
        public ?string $text,
        public ?array $callToActions,
        public ?string $imageId,
        public ?string $imageUrl = null,
        public ?string $imageHtml = null,
        public ?string $blockStyle = null,
    ) {
    }

    public function hasImage(): bool
    {
        return isset($this->imageId) && ! is_null($this->imageId);
    }

    /**
     * @throws LinkableModelNotFoundException
     */
    public static function create(array $cardBlockData, ?string $imageUrl, ?string $imageHtml, ?string $blockStyle, array $buttonStyleClasses): self
    {
        $callToActions = [];
        if (! empty($cardBlockData['card_call_to_action'])) {
            foreach ($cardBlockData['card_call_to_action'] as $callToAction) {
                try {
                    $callToActions[] = CallToActionData::create($callToAction, $buttonStyleClasses);
                } catch (CallToActionNotDefinedException $ex) {
                    //if the data is not available, we do not create a call to action.
                }
            }
        }

        return new self(
            title: $cardBlockData['title'] ?? null,
            text: $cardBlockData['text'] ?? null,
            callToActions: $callToActions,
            imageId: $cardBlockData['image'] ?? null,
            imageUrl: $imageUrl,
            imageHtml: $imageHtml,
            blockStyle: $blockStyle
        );
    }
}
