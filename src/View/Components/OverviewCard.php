<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

use Illuminate\View\Component;

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
        return view('filament-flexible-content-blocks::components.overview-card');
    }
}
