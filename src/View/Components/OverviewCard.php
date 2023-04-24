<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

use Illuminate\View\Component;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;

class OverviewCard extends Component
{
    public function __construct(
            public ?string $title = null,
            public ?string $description = null,
            public ?string $image = null,
            public ?string $url = null,
        ) {
    }

    public function render()
    {
        $themePrefix = FilamentFlexibleBlocksConfig::getViewThemePrefix();

        return view("filament-flexible-content-blocks::components.{$themePrefix}overview-card");
    }
}
