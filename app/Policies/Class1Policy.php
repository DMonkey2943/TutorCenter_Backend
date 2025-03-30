<?php

namespace App\Policies;

use App\Models\Approve;
use App\Models\Class1;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class Class1Policy
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
    public function view(User $user, Class1 $class1): bool
    {
        Log::info('Class1Policy view() - $user->parent?->id: ' . $user->parent?->id);
        Log::info('Class1Policy view() - $class1?->parent_id: ' . $class1?->parent_id);
        Log::info('Class1Policy view() - $user->tutor?->id: ' . $user->tutor?->id);
        Log::info('Class1Policy view() - $class1?->tutor_id: ' . $class1?->tutor_id);

        return ($user->parent?->id === $class1?->parent_id)
            || ($user->tutor?->id !== null && $class1?->tutor_id !== null && $user->tutor?->id === $class1?->tutor_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'parent';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Class1 $class1): bool
    {
        return $user->parent?->id === $class1->parent_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Class1 $class1): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Class1 $class1): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Class1 $class1): bool
    {
        return $user->role === 'admin';
    }

    public function isTutor(User $user): bool
    {
        return $user->role === 'tutor';
    }

    public function isParent(User $user): bool
    {
        return $user->role === 'parent';
    }

    public function confirmTeaching(User $user, Class1 $class1): bool
    {
        // Log::info('Class1Policy view() - $user->tutor?->id: ' . $user->tutor?->id);
        // Log::info('Class1Policy view() - $class1->id: ' . $class1->id);

        // Kiểm tra nếu user là tutor và có bản ghi approve với status > 0
        if ($user->tutor?->id) {
            $approved = Approve::where('tutor_id', $user->tutor->id)
                ->where('class_id', $class1->id)
                ->whereIn('status', [1, 2])
                ->exists();

            // Log::info('Class1Policy view() - Approved status > 0: ' . ($approved ? 'true' : 'false'));

            // return $approved;
            return $approved && $class1->status != 1; // Kiểm tra thêm nếu lớp chưa có tutor nhận dạy (status != 1)
        }

        // Nếu không phải tutor, trả về false (hoặc thêm logic khác nếu cần)
        return false;
    }
}
