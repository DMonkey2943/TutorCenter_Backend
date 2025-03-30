<?php

namespace App\Policies;

use App\Models\Class1;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RatePolicy
{
    // Quyền cho admin: luôn được phép
    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true; // Admin có toàn quyền
        }
    }

    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Class1 $class): bool
    {
        return ($user->role === 'parent' && $user->parent->id === $class->parent_id)
            || ($user->role === 'tutor' && $user->tutor->id === $class->tutor_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Class1 $class): bool
    {
        return $user->role === 'parent'
            && $user->parent->isParentOfClass($class->id);
    }

    /**
     * Determine whether the user can update the model.
     */
    // public function update(User $user, Rate $rate): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can delete the model.
     */
    // public function delete(User $user, Rate $rate): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Rate $rate): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Rate $rate): bool
    // {
    //     //
    // }
}
