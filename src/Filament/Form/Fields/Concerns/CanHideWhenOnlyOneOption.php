<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns;

/**
 * @see FlexibleSelectField
 */
trait CanHideWhenOnlyOneOption
{
    protected bool $shouldHideWhenOnlyOneOption = false;

    public function hideWhenOnlyOneOption(bool $shouldHide = true): static
    {
        $this->shouldHideWhenOnlyOneOption = $shouldHide;

        return $this;
    }
}
