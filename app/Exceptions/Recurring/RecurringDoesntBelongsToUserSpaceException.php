<?php

namespace App\Exceptions\Recurring;

use App\Exceptions\ForbiddenException;

class RecurringDoesntBelongsToUserSpaceException extends ForbiddenException
{
    /**
     * The response message.
     *
     * @var string
     */
    protected $message = 'Esta conta não pertence à nenhum dos seus espaços. Nenhuma operação pode ser realizada.';
}
