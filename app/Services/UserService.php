<?php

namespace App\Services;

use App\Interfaces\Services\UserServiceInterface;
use App\Models\User;
use App\Repositories\UserRepository;

class UserService extends AbstractService implements UserServiceInterface
{
    /**
     * The user repository.
     * 
     * @var \App\Repositories\UserRepository
     */
    protected $repository = UserRepository::class;

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
