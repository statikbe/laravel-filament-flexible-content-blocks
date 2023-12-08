<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

use Illuminate\View\Component;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

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
        ?string $blockStyle = null
    ) {
        if ($data) {
            $this->card = $data;
        } else {
            $this->card = new CardData(
                title: $title,
                text: $text,
                callToActions: $callToActions,
                imageId: null,
                imageUrl: $imageUrl,
                imageHtml: $image,
                blockStyle: $blockStyle
            );
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

    public function isFullyClickable(): bool
    {
        return count($this->card->callToActions) === 1;
    }

    public function render()
    {
        $themePrefix = FilamentFlexibleBlocksConfig::getViewThemePrefix();

        //get another template if the block style is set:
        $templateSuffix = '';
        if ($this->card->blockStyle && ! empty(trim($this->card->blockStyle))) {
            $templateSuffix = '-'.$this->card->blockStyle;
        }

        return view("filament-flexible-content-blocks::components.{$themePrefix}card{$templateSuffix}");
    }
}
