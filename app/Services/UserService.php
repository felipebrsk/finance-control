<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    /**
     * The user repository.
     * 
     * @var \App\Repositories\UserRepository
     */
    protected $userRepository;

    /**
     * Create a new class instance.
     * 
     * @param \App\Repositories\UserRepository $userRepository
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Find the user by email.
     * 
     * @param string $email
     * @return \App\Models\User
     */
    public function findByEmail(string $email): User
    {
        return $this->userRepository->findByEmail($email);
    }
}
