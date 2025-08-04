<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Actions;

use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\Page;

/**
 * Hides the language switcher if there is only one locale configured.
 */
class FlexibleLocaleSwitcher extends LocaleSwitcher
{
    public function setUp(): void
    {
        parent::setUp();

        $this->visible(function (Page $page){
            return count($page::getResource()::getTranslatableLocales()) < 2;
        });
    }
}
