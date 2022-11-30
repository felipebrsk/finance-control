<?php

namespace App\Policies;

use App\Models\Spending;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpendingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spending  $spending
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Spending $spending)
    {
        return $user->spaces->contains($spending->space_id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spending  $spending
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Spending $spending)
    {
        return $user->spaces->contains($spending->space_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spending  $spending
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Spending $spending)
    {
        return $user->spaces->contains($spending->space_id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spending  $spending
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Spending $spending)
    {
        return $user->spaces->contains($spending->space_id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spending  $spending
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Spending $spending)
    {
        return $user->spaces->contains($spending->space_id);
    }
}
