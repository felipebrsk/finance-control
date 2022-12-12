<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
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

    /**
     * All users to weekly report.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allToWeeklyReport(): Collection;
}
