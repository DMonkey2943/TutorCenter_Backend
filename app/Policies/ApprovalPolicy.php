<?php

namespace App\Policies;

use App\Models\Approve;
use App\Models\Class1;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class ApprovalPolicy
{
    // Quyền cho admin: luôn được phép
    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true; // Admin có toàn quyền
        }
    }

    /**
     * Xác định xem user có thể xem danh sách tutor đã approve không
     */
    public function viewApprovedTutors(User $user, Approve $approve)
    {
        // Log::info('viewApprovedTutors() - $approve: ' . $approve);
        // Log::info('viewApprovedTutors() - $user->role: ' . $user->role);
        // Log::info('viewApprovedTutors() - $user->parent->isParentOfClass($approve->class_id): ' . $user->parent->isParentOfClass($approve->class_id));
        return $user->role === 'parent' && $user->parent->isParentOfClass($approve->class_id);
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
    // public function view(User $user, Approve $approve): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Class1 $class): bool
    {
        // Log::info('create() - $user->role: ' . $user->role);
        // Log::info('create() - $user->tutor->profile_status: ' . $user->tutor->profile_status);
        // Log::info('create() - $class->status: ' . $class->status);
        return $class->status === 0 //Lớp chưa giao
            && $user->role === 'tutor'
            && $user->tutor->profile_status === 1; //Hồ sơ gia sư được duyệt
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Approve $approve): bool
    {
        return $user->role === 'parent'
            && $user->parent->isParentOfClass($approve->class_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Approve $approve): bool
    {
        Log::info('delete() - $user->role: ' . $user->role);
        Log::info('delete() - $user->tutor->id: ' . $user->tutor->id);
        Log::info('delete() - $approve: ' . $approve);
        return $user->role === 'tutor'
            && $user->tutor->id === $approve->tutor_id 
            && $approve->status === 0;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Approve $approve): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Approve $approve): bool
    {
        //
    }
}
