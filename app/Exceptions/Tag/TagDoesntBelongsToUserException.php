<?php

namespace App\Exceptions\Tag;

use App\Exceptions\ForbiddenException;

class TagDoesntBelongsToUserException extends ForbiddenException
{
    /**
     * The response message.
     *
     * @var string
     */
    protected $message = 'Uma ou mais tags não pertencem ao seu usuário. Nenhuma operação pode ser feita. Tente criar uma nova tag e repetir o processo.';
}
