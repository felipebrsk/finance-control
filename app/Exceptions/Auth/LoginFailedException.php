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
    protected $message = 'We could not find the user or the password is wrong. Please, double check the informations and try again!';
}
