<?php

namespace App\Filament\Resources\TranslatableFlexiblePageResource\Pages;

use App\Filament\Resources\TranslatablePageResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Resource\Pages\Actions\CopyContentBlocksToLocalesAction;

class EditTranslatablePage extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = TranslatablePageResource::class;

    protected function getActions(): array
    {
        return [
            CopyContentBlocksToLocalesAction::make(),
            LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
