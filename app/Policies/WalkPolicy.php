<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Walk;
use Illuminate\Auth\Access\Response;

class WalkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(user $user, walk $walk): bool
    {
        return $user->id === $walk->dog->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Walk $walk): bool
    {
        return $user->id === $walk->dog->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Walk $walk): bool
    {
        return $user->id === $walk->dog->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Walk $walk): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Walk $walk): bool
    {
        return false;
    }
}
