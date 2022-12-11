<?php

namespace App\Exceptions\Spending;

use App\Exceptions\ForbiddenException;

class SpendingDoesntBelongsToUserSpaceException extends ForbiddenException
{
    /**
     * The response message.
     *
     * @var string
     */
    protected $message = 'Esta conta não pertence à nenhum dos seus espaços. Nenhuma operação pode ser realizada.';
}
