<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data;

use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CardsBlock;
use Statikbe\FilamentFlexibleContentBlocks\Exceptions\CallToActionNotDefinedException;
use Statikbe\FilamentFlexibleContentBlocks\Exceptions\LinkableModelNotFoundException;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockIdField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionField;

class CardData
{
    /**
     * @param  array<CallToActionData>|null  $callToActions
     */
    public function __construct(
        public string $cardId,
        public ?string $title,
        public ?string $text,
        public ?array $callToActions,
        public bool $hasImage = false,
        public ?string $imageUrl = null,
        public ?string $imageHtml = null,
        public ?string $blockStyle = null,
    ) {}

    public function hasImage(): bool
    {
        return $this->hasImage;
    }

    /**
     * @throws LinkableModelNotFoundException
     */
    public static function create(array $cardBlockData, CardsBlock $cardsBlock): self
    {
        $cardId = $cardBlockData[BlockIdField::FIELD] ?? BlockIdField::generateBlockId();
        $hasImage = $cardsBlock->hasImage($cardId);
        $imageUrl = $hasImage ? $cardsBlock->getCardImageUrl($cardId) : null;
        $imageHtml = $hasImage ? $cardsBlock->getCardImageMedia($cardId, $cardBlockData['title']) : null;
        $blockStyle = $cardsBlock->hasDefaultBlockStyle() ? null : $cardsBlock->blockStyle;
        $buttonStyleClasses = CallToActionField::getButtonStyleClasses($cardsBlock::class);

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
            cardId: $cardId,
            title: $cardBlockData['title'] ?? null,
            text: $cardBlockData['text'] ?? null,
            callToActions: $callToActions,
            hasImage: $hasImage,
            imageUrl: $imageUrl,
            imageHtml: $imageHtml,
            blockStyle: $blockStyle
        );
    }
}
