<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\TagsInput;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component as Livewire;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\HasTranslatableHint;

class SEOKeywordsField extends TagsInput
{
    use HasTranslatableHint;

    public const FIELD = 'seo_keywords';

    public static function create(bool $required = false): static
    {
        return static::make(self::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.seo_keywords_lbl'))
            ->required($required)
            ->suggestions(function (?Model $record, Livewire $livewire): array {
                if (! $record) {
                    return [];
                }

                $locale = match (true) {
                    method_exists($livewire, 'getActiveSchemaLocale') => $livewire->getActiveSchemaLocale(),
                    property_exists($livewire, 'activeLocale') => $livewire->activeLocale,
                    default => null,
                };

                $query = $locale
                    ? $record::select("seo_keywords->$locale as seo_keywords")
                        ->whereNotNull("seo_keywords->$locale")
                    : $record::select('seo_keywords')
                        ->whereNotNull('seo_keywords');

                return $query->get()
                    ->flatMap(fn (Model $item) => collect($item->seo_keywords ?? [])->flatten())
                    ->filter(fn ($value): bool => is_string($value))
                    ->unique()
                    ->values()
                    ->all();
            })
            ->addsTranslatableHint();
    }
}
