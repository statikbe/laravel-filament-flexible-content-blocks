<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource\Pages;

use Filament\Actions;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\EditRecord\Concerns\TranslatableWithMedia;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Resource\Pages\Actions\CopyContentBlocksToLocalesAction;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource;

class EditTranslatablePage extends EditRecord
{
    use TranslatableWithMedia;

    protected static string $resource = TranslatablePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CopyContentBlocksToLocalesAction::make(),
            LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
