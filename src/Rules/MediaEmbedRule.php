<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Rules;

use Illuminate\Contracts\Validation\Rule;
use MediaEmbed\MediaEmbed;

class MediaEmbedRule implements Rule
{
    private MediaEmbed $mediaEmbed;

    public function __construct()
    {
        $this->mediaEmbed = new MediaEmbed();
    }

    public function passes($attribute, $value): bool
    {
        if ($value) {
            $parsedUrl = $this->mediaEmbed->parseUrl($value);
            if ($parsedUrl) {
                return true;
            }
        }

        return false;
    }

    public function message(): string
    {
        return trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.media_embed.validation');
    }
}
