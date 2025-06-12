<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use MediaEmbed\MediaEmbed;

class MediaEmbedRule implements ValidationRule
{
    private MediaEmbed $mediaEmbed;

    public function __construct()
    {
        $this->mediaEmbed = new MediaEmbed;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value || ! $this->mediaEmbed->parseUrl($value)) {
            $fail(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.media_embed.validation'));
        }
    }
}
