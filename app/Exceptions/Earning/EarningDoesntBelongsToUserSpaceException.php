<?php

namespace App\Exceptions\Earning;

use App\Exceptions\ForbiddenException;

class EarningDoesntBelongsToUserSpaceException extends ForbiddenException
{
    /**
     * The response message.
     * 
     * @var string
     */
    protected $message = 'Esta conta não pertence à nenhum dos seus espaços. Nenhuma operação pode ser realizada.';
}
