<?php

namespace App\Exceptions\Space;

use App\Exceptions\ForbiddenException;

class SpaceDoesntBelongsToUserException extends ForbiddenException
{
    /**
     * The response message.
     *
     * @var string
     */
    protected $message = 'O espaço não pertence ao seu usário. Nenhuma operação pode ser realizada.';
}
