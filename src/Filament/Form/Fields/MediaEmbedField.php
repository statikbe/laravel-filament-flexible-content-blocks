<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields;

use Filament\Forms\Components\TextInput;

use \Closure;
use Statikbe\FilamentFlexibleContentBlocks\Rules\MediaEmbedRule;

class MediaEmbedField extends TextInput
{

    protected bool|Closure $isUrl = true;

    protected static function getFieldName(): string
    {
        return 'media_embed';
    }

    public static function create(bool $required = false): static
    {
        $field = static::getFieldName();

        return static::make($field)
                     ->label(trans("filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.{$field}_lbl"))
                     ->maxLength(255)
                     ->media()
                     ->required($required);
    }


    public function media(bool|Closure $condition = true): static
    {
        $this->media = $condition;

        $this->rule(new MediaEmbedRule(), $condition);

        return $this;
    }

    public function url(bool|Closure $condition = true): static
    {
        $this->isUrl = $condition;

        $this->rule('url', $condition);

        return $this;
    }

    public function isUrl(): bool
    {
        return (bool)$this->evaluate($this->isUrl);
    }
}

