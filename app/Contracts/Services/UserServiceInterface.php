<?php

namespace App\Contracts\Services;

use Illuminate\Database\Eloquent\Model;

interface UserServiceInterface extends BasicServiceInterface
{
    /**
     * Find the user by email.
     *
     * @param string $email
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findByEmail(string $email): Model;
}
