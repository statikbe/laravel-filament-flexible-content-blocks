<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource;

class ListTranslatablePages extends ListRecords
{
    use Translatable;

    protected static string $resource = TranslatablePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            CreateAction::make(),
        ];
    }
}
