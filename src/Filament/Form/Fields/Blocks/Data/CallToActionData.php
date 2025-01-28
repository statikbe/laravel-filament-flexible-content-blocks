<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Log;
use Statikbe\FilamentFlexibleContentBlocks\Exceptions\CallToActionNotDefinedException;
use Statikbe\FilamentFlexibleContentBlocks\Exceptions\LinkableModelNotFoundException;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type\CallToActionType;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable;

class CallToActionData
{
    public function __construct(
        public ?string $url,
        public ?string $label = null,
        public ?string $buttonStyle = null,
        public bool $openNewWindow = false,
    ) {}

    /**
     * @param  array{cta_model: string, entry_id: ?string, url: ?string, button_style: ?string, button_label: ?string, button_open_new_window: ?bool}  $callToActionBlockData
     *
     * @throws LinkableModelNotFoundException
     * @throws CallToActionNotDefinedException
     */
    public static function create(array $callToActionBlockData, array $buttonStyleClasses): self
    {
        if (! ($callToActionBlockData[CallToActionField::FIELD_URL] ||
                $callToActionBlockData[CallToActionField::FIELD_ROUTE] ||
                $callToActionBlockData[CallToActionField::FIELD_CTA_MODEL])) {
            throw CallToActionNotDefinedException::create('The call to action data does not specify a route, url or model.');
        }

        $url = null;
        if ($callToActionBlockData[CallToActionField::FIELD_CTA_MODEL] === CallToActionType::TYPE_URL) {
            $url = $callToActionBlockData[CallToActionField::FIELD_URL];
        } elseif ($callToActionBlockData[CallToActionField::FIELD_CTA_MODEL] === CallToActionType::TYPE_ROUTE) {
            $url = route($callToActionBlockData[CallToActionField::FIELD_ROUTE]);
        } else {
            $linkableType = $callToActionBlockData[CallToActionField::FIELD_CTA_MODEL];
            /** @var class-string<Linkable&Model> $linkableModel */
            $linkableModel = Relation::getMorphedModel($linkableType);

            if (! $linkableModel) {
                throw new LinkableModelNotFoundException("No linkable model could be found for '{$linkableType}' in morph map.");
            }

            try {
                /** @var Linkable&Model $page */
                $page = $linkableModel::findOrFail($callToActionBlockData[CallToActionField::FIELD_ENTRY_ID]);
                $url = $page->getViewUrl();
            } catch (ModelNotFoundException $ex) {
                // The url could not be created because the entry could not be found. By catching the exception, we avoid a 404.
                Log::warning("The url could not be created because the entry could not be found ({$linkableModel}: {$callToActionBlockData[CallToActionField::FIELD_ENTRY_ID]}). This will probably result in a dead link.");
            }
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
