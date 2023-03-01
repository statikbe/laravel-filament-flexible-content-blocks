<?php

namespace App\Filament\Resources\TranslatableFlexiblePageResource\Pages;

use App\Filament\Resources\TranslatablePageResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateTranslatablePage extends CreateRecord
{
    use Translatable;

    protected static string $resource = TranslatablePageResource::class;
}
