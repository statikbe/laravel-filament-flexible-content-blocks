<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\Select;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasPageAttributes;

class ParentField extends Select
{
    const FIELD = 'parent_id';

    protected static function resolveModelClass(self $component): ?string
    {
        $livewire = $component->getLivewire();

        if ($livewire instanceof Page) {
            return $livewire::getResource()::getModel();
        }

        if (method_exists($livewire, 'getRecord') && $record = $livewire->getRecord()) {
            return get_class($record);
        }

        return null;
    }

    public static function create(): static
    {
        return static::make(static::FIELD)
            ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.parent_lbl'))
            ->helperText(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.parent_help'))
            ->searchable()
            ->getSearchResultsUsing(function (string $search, self $component): array {
                $modelClass = static::resolveModelClass($component);

                if (! $modelClass) {
                    return [];
                }

                $locales = config('filament-flexible-content-blocks.supported_locales', [app()->getLocale()]);

                return $modelClass::query()
                    ->where(function ($query) use ($search, $locales) {
                        foreach ($locales as $locale) {
                            $query->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, ?))) LIKE ?', [
                                '$."'.$locale.'"',
                                '%'.mb_strtolower($search).'%',
                            ]);
                        }
                    })
                    ->limit(50)
                    ->pluck('title', 'id')
                    ->toArray();
            })
            ->getOptionLabelUsing(function ($value, self $component): ?string {
                $modelClass = static::resolveModelClass($component);

                if (! $modelClass) {
                    return null;
                }

                return $modelClass::find($value)?->getAttribute('title');
            })
            ->required(false);
    }
}
