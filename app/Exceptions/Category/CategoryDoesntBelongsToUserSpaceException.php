<?php

namespace App\Exceptions\Category;

use App\Exceptions\ForbiddenException;

class CategoryDoesntBelongsToUserSpaceException extends ForbiddenException
{
    /**
     * The response message.
     * 
     * @var string
     */
    protected $message = 'Esta categoria não pertence à nenhum dos seus espaços. Nenhuma operação pode ser realizada.';
}
