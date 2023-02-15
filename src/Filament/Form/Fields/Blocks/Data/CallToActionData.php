<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type\CallToActionType;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable;

class CallToActionData
{
    public function __construct(
            public string $url,
            public ?string $label = null,
            public ?string $buttonStyle = null,
            public bool $openNewWindow = false,
        ) {
    }

    /**
     * @param  array{cta_model: string, entry_id: ?string, url: ?string, button_style: ?string, button_label: ?string, button_open_new_window: ?boolean}  $callToActionBlockData
     * @return self
     */
    public static function create(array $callToActionBlockData, array $buttonStyleClasses): self
    {
        if ($callToActionBlockData['cta_model'] === CallToActionType::TYPE_URL) {
            $url = $callToActionBlockData['url'];
        } else {
            $linkableType = $callToActionBlockData['cta_model'];
            /** @var class-string<Linkable&Model> $linkableModel */
            $linkableModel = Relation::getMorphedModel($linkableType);

            /** @var Linkable&Model $page */
            $page = $linkableModel::findOrFail($callToActionBlockData['entry_id']);
            $url = $page->getViewUrl();
        }

        $buttonStyle = $callToActionBlockData['button_style'] ?? null;
        if ($buttonStyleClasses[$buttonStyle]) {
            $buttonStyle = $buttonStyleClasses[$buttonStyle];
        }

        return new self(
            $url,
            $callToActionBlockData['button_label'] ?? null,
            $buttonStyle,
            $callToActionBlockData['button_open_new_window'] ?? false,
        );
    }
}
