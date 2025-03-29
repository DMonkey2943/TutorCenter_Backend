<?php

namespace App\Policies;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class TutorPolicy
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
    public function view(User $user, Tutor $tutor): bool
    {
        return ($user->tutor?->id === $tutor->id) || $user->role === 'parent';
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
    public function update(User $user, Tutor $tutor): bool
    {
        return ($user->tutor?->id === $tutor->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tutor $tutor): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Tutor $tutor): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, Tutor $tutor): bool
    // {
    //     //
    // }

    public function isParent(User $user): bool
    {
        return $user->role === 'parent';
    }

    public function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }
}
