<?php

namespace App\Policies;

use App\Models\Kelompok;
use App\Models\Pengguna;

class GroupPolicy
{
    public function view(User $user, Group $group): bool
    {
        // Admin and koordinator can view all groups
        if (in_array($user->role, ['admin', 'koordinator'])) {
            return true;
        }

        // Dosen can view groups in their projects
        if ($user->role === 'dosen' && $group->project->dosen_id === $user->id) {
            return true;
        }

        // Mahasiswa can view their own groups
        if ($user->role === 'mahasiswa' && $group->members->contains($user)) {
            return true;
        }

        return false;
    }

    public function update(User $user, Group $group): bool
    {
        // Only group members can update group progress
        if ($user->role === 'mahasiswa' && $group->members->contains($user)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Group $group): bool
    {
        // Only admin and project supervisor can delete groups
        return in_array($user->role, ['admin']) || 
               ($user->role === 'dosen' && $group->project->dosen_id === $user->id);
    }
}
