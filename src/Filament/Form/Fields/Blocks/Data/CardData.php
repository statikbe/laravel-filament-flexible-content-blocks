<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data;

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

    public static function create(array $cardBlockData, ?string $imageUrl, ?string $imageHtml, ?string $blockStyle, array $buttonStyleClasses): self
    {
        $callToActions = [];
        if (! empty($cardBlockData['card_call_to_action'])) {
            foreach ($cardBlockData['card_call_to_action'] as $callToAction) {
                $callToActions[] = CallToActionData::create($callToAction, $buttonStyleClasses);
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
