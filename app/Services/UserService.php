<?php

namespace App\Services;

use App\Models\User;
use App\Contracts\Services\UserServiceInterface;
use App\Contracts\Repositories\UserRepositoryInterface;

class UserService extends AbstractService implements UserServiceInterface
{
    /**
     * The user repository.
     * 
     * @var \App\Contracts\Repositories\UserRepositoryInterface
     */
    protected $repository = UserRepositoryInterface::class;

    /**
     * Find the user by email.
     * 
     * @param string $email
     * @return \App\Models\User
     */
    public function findByEmail(string $email): User
    {
        return $this->repository->findByEmail($email);
    }
}
