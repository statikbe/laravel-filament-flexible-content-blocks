<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

use Illuminate\View\Component;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData;

class CallToAction extends Component
{
    public CallToActionData $callToAction;

    public bool $isFullyClickable = false;

    public function __construct(
            ?CallToActionData $data = null,
            string $url = null,
            ?string $label = null,
            ?string $buttonStyle = null,
            bool $openNewWindow = false,
            bool $isFullyClickable = false,
        ) {
        if ($data) {
            $this->callToAction = $data;
        } else {
            $this->callToAction = new CallToActionData(
                $url,
                $label,
                $buttonStyle,
                $openNewWindow
            );
        }
        $this->isFullyClickable = $isFullyClickable;
    }

    public function render()
    {
        return view('filament-flexible-content-blocks::components.call-to-action');
    }
}
