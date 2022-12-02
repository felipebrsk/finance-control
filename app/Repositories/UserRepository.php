<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends AbstractRepository
{
    /**
     * Find user by email.
     * 
     * @param string $email
     * @return \App\Models\User
     */
    public function findByEmail(string $email): User
    {
        return User::findByEmail($email);
    }
}
