<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource;

class ListTranslatablePages extends ListRecords
{
    use Translatable;

    protected static string $resource = TranslatablePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
