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
    protected $message = 'Uma ou mais tags não pertencem ao seu usuário e não foi possível associá-la. Tente criar uma nova tag e repetir o processo.';
}
