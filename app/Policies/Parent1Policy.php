<?php

namespace App\Policies;

use App\Models\Parent1;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class Parent1Policy
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
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Parent1 $parent1): bool
    {
        return ($user->parent?->id === $parent1->id);
    }

    /**
     * Determine whether the user can create models.
     */
    // public function create(User $user): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Parent1 $parent1): bool
    {
        return ($user->parent?->id === $parent1->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Parent1 $parent1): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Parent1 $parent1): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Parent1 $parent1): bool
    // {
    //     //
    // }
}
