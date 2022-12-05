<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface extends BasicRepositoryInterface
{
    /**
     * Find user by email.
     * 
     * @param string $email
     * @return \App\Models\User
     */
    public function findByEmail(string $email): Model;
}
