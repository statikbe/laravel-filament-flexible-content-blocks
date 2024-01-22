<?php

namespace App\Filament\Resources\TranslatableFlexiblePageResource\Pages;

use App\Filament\Resources\TranslatablePageResource;
use Filament\Resources\Pages\CreateRecord;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\CreateRecord\Concerns\TranslatableWithMedia;

class CreateTranslatablePage extends CreateRecord
{
    use TranslatableWithMedia;

    protected static string $resource = TranslatablePageResource::class;
}
