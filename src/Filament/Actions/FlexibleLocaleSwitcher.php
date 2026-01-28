<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Actions;

use Filament\Pages\Page;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;

/**
 * Hides the language switcher if there is only one locale configured.
 */
class FlexibleLocaleSwitcher extends LocaleSwitcher
{
    public function setUp(): void
    {
        parent::setUp();

        $this->visible(function (Page $livewire) {
            // @phpstan-ignore-next-line - getResource() exists on resource pages
            if (method_exists($livewire::class, 'getResource') && method_exists($livewire::getResource(), 'getTranslatableLocales')) {
                // @phpstan-ignore-next-line - getResource() exists on resource pages
                return count($livewire::getResource()::getTranslatableLocales()) > 1;
            }

            return false;
        });
    }
}
