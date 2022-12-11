<?php

namespace App\Exceptions\Auth;

use App\Exceptions\UnauthorizedException;

class LoginFailedException extends UnauthorizedException
{
    /**
     * The response message.
     *
     * @var string
     */
    protected $message = 'Não localizamos o seu usuário. Por favor, revise os dados e tente novamente!';
}
