<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable;

class SlugField extends TextInput
{
    const FIELD = 'slug';

    const URL_REPLACEMENT_SLUG = '__replace_slug__';

    public static function create(bool $disabled = true): static
    {
        return self::make(self::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.slug_lbl'))
            ->disabled($disabled)
            ->helperText(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.slug_desc'))
            ->prefix(function (Page $livewire) {
                $url = self::getUrlWithReplacementSlug($livewire);

                return Str::before($url, self::URL_REPLACEMENT_SLUG);
            })
            ->suffix(function (Page $livewire) {
                $url = self::getUrlWithReplacementSlug($livewire);

                return Str::after($url, self::URL_REPLACEMENT_SLUG);
            })
            //make the slug required on edit. on create, the slug is not required so if kept blank a slug is generated.
            ->required(fn (?Model $record): bool => $record && $record->id > 0);
    }

    private static function getUrlWithReplacementSlug(Page $livewire): string
    {
        $linkModel = $livewire->getResource()::getModel();
        /* @var Linkable $link */
        $link = new $linkModel();
        $link->slug = self::URL_REPLACEMENT_SLUG;

        return $link->getViewUrl();
    }
}
