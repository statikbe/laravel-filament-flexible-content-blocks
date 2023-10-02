<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Exceptions;

class LinkableModelNotFoundException extends \Exception
{
    public static function create(string $message)
    {
        return new self($message);
    }
}
