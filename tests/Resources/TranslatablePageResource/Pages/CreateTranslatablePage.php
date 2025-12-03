<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\CreateRecord\Concerns\TranslatableWithMedia;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource;

class CreateTranslatablePage extends CreateRecord
{
    use TranslatableWithMedia;

    protected static string $resource = TranslatablePageResource::class;
}
