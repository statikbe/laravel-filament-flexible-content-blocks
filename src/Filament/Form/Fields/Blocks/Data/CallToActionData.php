<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Statikbe\FilamentFlexibleContentBlocks\Exceptions\LinkableModelNotFoundException;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionField;
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
     *
     * @throws LinkableModelNotFoundException
     */
    public static function create(array $callToActionBlockData, array $buttonStyleClasses): self
    {
        if ($callToActionBlockData[CallToActionField::FIELD_CTA_MODEL] === CallToActionType::TYPE_URL) {
            $url = $callToActionBlockData[CallToActionField::FIELD_URL];
        } elseif ($callToActionBlockData[CallToActionField::FIELD_CTA_MODEL] === CallToActionType::TYPE_ROUTE) {
            $url = route($callToActionBlockData[CallToActionField::FIELD_ROUTE]);
        } else {
            $linkableType = $callToActionBlockData[CallToActionField::FIELD_CTA_MODEL];
            /** @var class-string<Linkable&Model> $linkableModel */
            $linkableModel = Relation::getMorphedModel($linkableType);

            if (!$linkableModel) {
                throw new LinkableModelNotFoundException("No linkable model could be found for '{$linkableType}'");
            }

            /** @var Linkable&Model $page */
            $page = $linkableModel::findOrFail($callToActionBlockData[CallToActionField::FIELD_ENTRY_ID]);
            $url = $page->getViewUrl();
        }

        $buttonStyle = $callToActionBlockData[CallToActionField::FIELD_BUTTON_STYLE] ?? null;
        if (isset($buttonStyleClasses[$buttonStyle])) {
            $buttonStyle = $buttonStyleClasses[$buttonStyle];
        }

        return new self(
            $url,
            $callToActionBlockData[CallToActionField::FIELD_BUTTON_LABEL] ?? null,
            $buttonStyle,
            $callToActionBlockData[CallToActionField::FIELD_BUTTON_OPEN_NEW_WINDOW] ?? false,
        );
    }
}
