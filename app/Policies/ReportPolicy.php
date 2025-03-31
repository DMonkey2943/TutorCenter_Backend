<?php

namespace App\Policies;

use App\Models\Class1;
use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportPolicy
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
    public function view(User $user, Report $report): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Class1 $class): bool
    {
        return $user->role === 'tutor'
            && $user->tutor->id === $class->tutor_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Report $report): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Report $report): bool
    // {
    //     //
    // }

    public function isTutorTeachingClass(User $user, Class1 $class): bool
    {
        return $user->role === 'tutor'
            && $user->tutor->id === $class->tutor_id;
    }
}
