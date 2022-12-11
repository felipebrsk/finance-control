<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends AbstractRepository
{
    /**
     * The repository model.
     *
     * @var \App\Models\User
     */
    protected $model = User::class;

    /**
     * Find user by email.
     *
     * @param string $email
     * @return \App\Models\User
     */
    public function findByEmail(string $email): User
    {
        return $this->model::findByEmail($email);
    }
}
