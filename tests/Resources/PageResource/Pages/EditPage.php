<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\PageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\PageResource;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
