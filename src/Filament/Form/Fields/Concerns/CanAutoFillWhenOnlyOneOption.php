<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns;

trait CanAutoFillWhenOnlyOneOption
{
    const AUTO_FILL_MODE_DISABLED = 'disabled';

    const AUTO_FILL_MODE_ONLY_ONE_AND_REQUIRED = 'only-one-and-required';

    const AUTO_FILL_MODE_ONLY_ONE = 'only-one';

    protected string $autoFillModeWhenOnlyOneOption = self::AUTO_FILL_MODE_DISABLED;

    public function autoFillWhenOnlyOneOptionAndRequired(): static
    {
        $this->autoFillModeWhenOnlyOneOption = self::AUTO_FILL_MODE_ONLY_ONE_AND_REQUIRED;
        return $this;
    }

    public function autoFillWhenOnlyOneOption(): static
    {
        $this->autoFillModeWhenOnlyOneOption = self::AUTO_FILL_MODE_ONLY_ONE;
        return $this;
    }

    public function noAutoFill(): static
    {
        $this->autoFillModeWhenOnlyOneOption = self::AUTO_FILL_MODE_DISABLED;
        return $this;
    }

    public function isAutoFillModeDisabled(): bool
    {
        return $this->autoFillModeWhenOnlyOneOption === self::AUTO_FILL_MODE_DISABLED;
    }

    public function isAutoFillModeOnlyOneAndRequired(): bool
    {
        return $this->autoFillModeWhenOnlyOneOption === self::AUTO_FILL_MODE_ONLY_ONE_AND_REQUIRED;
    }

    public function isAutoFillModeOnlyOne(): bool
    {
        return $this->autoFillModeWhenOnlyOneOption === self::AUTO_FILL_MODE_ONLY_ONE;
    }
}
