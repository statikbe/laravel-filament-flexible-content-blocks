<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Exceptions;

use Illuminate\Database\Eloquent\Model;

class LinkableModelNotFoundException extends \Exception
{
    private ?Model $record;

    public static function create(string $message)
    {
        return new self($message);
    }

    public function setRecord(Model $record){
        $this->record = $record;

        $this->message = $this->message . " of record {$record::class} {$record->id}";
    }
}
