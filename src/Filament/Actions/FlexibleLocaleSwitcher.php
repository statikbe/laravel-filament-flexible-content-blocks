<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Actions;

use Filament\Actions\LocaleSwitcher;
use Filament\Pages\Page;

/**
 * Hides the language switcher if there is only one locale configured.
 */
class FlexibleLocaleSwitcher extends LocaleSwitcher
{
    public function setUp(): void
    {
        parent::setUp();

        $this->visible(function (Page $livewire) {
            if (method_exists($livewire::class, 'getResource') && method_exists($livewire::getResource(), 'getTranslatableLocales')) {
                return count($livewire::getResource()::getTranslatableLocales()) > 1;
            }

            return false;
        });
    }
}
