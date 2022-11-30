<?php

namespace App\Policies;

use App\Models\Earning;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EarningPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Earning  $earning
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Earning $earning)
    {
        return $user->spaces->contains($earning->space_id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Earning  $earning
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Earning $earning)
    {
        return $user->spaces->contains($earning->space_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Earning  $earning
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Earning $earning)
    {
        return $user->spaces->contains($earning->space_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Earning  $earning
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Earning $earning)
    {
        return $user->spaces->contains($earning->space_id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Earning  $earning
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Earning $earning)
    {
        return $user->spaces->contains($earning->space_id);
    }
}
