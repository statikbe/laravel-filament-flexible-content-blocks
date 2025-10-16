<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Generic;

use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\CanAutoFillWhenOnlyOneOption;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Concerns\CanHideWhenOnlyOneOption;

class GenericSelectField extends Select
{
    use CanAutoFillWhenOnlyOneOption;
    use CanHideWhenOnlyOneOption;

    private ?array $cachedOptions = null;

    /**
     * This 'setup' is immediately called on "make"
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** By default, the Select will be hidden when there is only one option. */
        $this->hideWhenOnlyOneOption();
        /** By default, the Select will be auto-filled when there is only one option and when the field is required. */
        $this->autoFillWhenOnlyOneOptionAndRequired();

        $this->default(function () {
            if ($this->hasOnlyOneOption()) {
                if (
                    ($this->isAutoFillModeOnlyOneAndRequired() && $this->isRequired())
                    || $this->isAutoFillModeOnlyOne()
                ) {
                    return collect($this->getCachedOptions())->first();
                }
            }

            return null;
        });
    }

    protected function getCachedOptions(): array
    {
        if (! $this->cachedOptions) {
            $this->cachedOptions = $this->getOptions();
        }

        return $this->cachedOptions;
    }

    protected function hasOnlyOneOption(): bool
    {
        return is_array($this->getCachedOptions()) && count($this->getCachedOptions()) === 1;
    }

    public function isHidden(): bool
    {
        /** Checking if the field is hidden anyway based on if the field has a hidden(..) or a visible(..) function defined on them */
        $isFieldHiddenAnyway = parent::isHidden();

        if ($isFieldHiddenAnyway) {
            return true;
        }

        if ($this->shouldHideWhenOnlyOneOption && $this->hasOnlyOneOption()) {
            return true;
        }

        return false;
    }
}
