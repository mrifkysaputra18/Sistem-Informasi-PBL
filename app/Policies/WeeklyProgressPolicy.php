<?php

namespace App\Policies;

use App\Models\Pengguna;
use App\Models\KemajuanMingguan;
use Illuminate\Auth\Access\Response;

class WeeklyProgressPolicy
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
    public function view(User $user, WeeklyProgress $weeklyProgress): bool
    {
        return false;
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
    public function update(User $user, WeeklyProgress $weeklyProgress): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WeeklyProgress $weeklyProgress): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WeeklyProgress $weeklyProgress): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WeeklyProgress $weeklyProgress): bool
    {
        return false;
    }
}


