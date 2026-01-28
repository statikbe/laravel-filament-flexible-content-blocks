<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Support\Icons\Heroicon;

class HeroVideoUrlField extends MediaEmbedField
{
    public static function create(bool $required = false): static
    {
        $field = parent::create($required);

        return $field
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.hero_video_url_lbl'))
            ->hint(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.hero_video_url_help'))
            ->hintIcon(Heroicon::QuestionMarkCircle)
            ->url();
    }

    public static function getFieldName(): string
    {
        return 'hero_video_url';
    }
}
