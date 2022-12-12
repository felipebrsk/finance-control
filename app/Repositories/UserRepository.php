<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Contracts\Repositories\UserRepositoryInterface;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
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

    /**
     * All users to weekly report.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allToWeeklyReport(): Collection
    {
        return $this->model::whereWeeklyReport(true)->get();
    }
}
