<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Replacer;

interface TextParameterReplacer
{
    /**
     * Returns an array with the parameter names as key and the replacement value as value.
     *
     * @return array<string, string|numeric|null>
     */
    public function getParameters(): array;
}
