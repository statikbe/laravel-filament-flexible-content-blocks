<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasTranslatableHint;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable;

class SlugField extends TextInput
{
    use HasTranslatableHint;

    const FIELD = 'slug';

    const URL_REPLACEMENT_SLUG = '__replace_slug__';

    public static function create(bool $disabled = true): static
    {
        return static::make(static::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.slug_lbl'))
            ->disabled($disabled)
            ->helperText(new HtmlString(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.slug_desc')))
            ->prefix(function (Page $livewire) {
                $url = static::getUrlWithReplacementSlug($livewire);

                return Str::before($url, static::URL_REPLACEMENT_SLUG);
            })
            ->suffix(function (Page $livewire) {
                $url = static::getUrlWithReplacementSlug($livewire);

                return Str::after($url, static::URL_REPLACEMENT_SLUG);
            })
            // make the slug required on edit. on create, the slug is not required so if kept blank a slug is generated.
            ->required(fn (?Model $record): bool => $record && $record->getKey() > 0)
            // hide slug field on creation, because the spatie sluggable will overwrite it anyways:
            ->hidden(function (?Model $record, Page $livewire, Get $get, Set $set): bool {
                if (empty($record)) {
                    return true;
                }

                if (isset($record->translatable) && in_array(static::FIELD, $record->translatable)) {
                    if (method_exists($livewire, 'getActiveFormsLocale') && method_exists($record, 'getTranslation')) {
                        $locale = $livewire->getActiveFormsLocale();

                        $noSlug = empty($record->getTranslation(static::FIELD, $locale, false));
                        // update translated slug in form:
                        if (! $noSlug && empty($get(self::FIELD))) {
                            $set(self::FIELD, $record->getTranslation(static::FIELD, $locale, false));
                        }

                        return $noSlug;
                    }
                }

                return false;
            })
            ->addsTranslatableHint();
    }

    protected static function getUrlWithReplacementSlug(Page $livewire): string
    {
        $linkModel = $livewire->getResource()::getModel();
        /* @var Linkable $link */
        $link = new $linkModel;

        if (method_exists($livewire, 'getActiveFormsLocale') && method_exists($link, 'setTranslation')) {
            $locale = $livewire->getActiveFormsLocale();
            $link->setTranslation('slug', $locale, static::URL_REPLACEMENT_SLUG);
        } else {
            $link->slug = static::URL_REPLACEMENT_SLUG;
            $locale = app()->getLocale();
        }

        return $link->getViewUrl($locale);
    }
}
