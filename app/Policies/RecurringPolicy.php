<?php

namespace App\Policies;

use App\Models\Recurring;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecurringPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Recurring  $recurring
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Recurring $recurring)
    {
        return $user->spaces->contains($recurring->space_id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Recurring  $recurring
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Recurring $recurring)
    {
        return $user->spaces->contains($recurring->space_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Recurring  $recurring
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Recurring $recurring)
    {
        return $user->spaces->contains($recurring->space_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Recurring  $recurring
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Recurring $recurring)
    {
        return $user->spaces->contains($recurring->space_id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Recurring  $recurring
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Recurring $recurring)
    {
        return $user->spaces->contains($recurring->space_id);
    }
}
