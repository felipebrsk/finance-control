<?php

namespace App\Exceptions\Recurring;

use App\Exceptions\BadRequestException;

class UnknownRecurringTypeException extends BadRequestException
{
    /**
     * Create new exception instance.
     * 
     * @param string $type
     * @return void
     */
    public function __construct(string $type)
    {
        $this->message = "Unknown type {$type} for recurring.";
    }
}
