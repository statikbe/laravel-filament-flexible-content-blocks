<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Exceptions;

use Exception;

class CallToActionNotDefinedException extends Exception
{
    public static function create(string $message)
    {
        return new self($message);
    }
}
