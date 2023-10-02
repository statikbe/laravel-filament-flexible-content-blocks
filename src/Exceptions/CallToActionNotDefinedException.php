<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Exceptions;

class CallToActionNotDefinedException extends \Exception
{
    public static function create(string $message)
    {
        return new self($message);
    }
}
