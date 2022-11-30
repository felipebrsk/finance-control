<?php

namespace App\Policies;

use App\Models\Space;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return bool
     */
    public function view(User $user, Space $space)
    {
        return $user->spaces->contains($space->id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return bool
     */
    public function update(User $user, Space $space)
    {
        return $user->spaces->contains($space->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return bool
     */
    public function delete(User $user, Space $space)
    {
        return $user->spaces->contains($space->id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return bool
     */
    public function restore(User $user, Space $space): bool
    {
        return $user->spaces->contains($space->id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Space  $space
     * @return bool
     */
    public function forceDelete(User $user, Space $space): bool
    {
        return $user->spaces->contains($space->id);
    }
}
