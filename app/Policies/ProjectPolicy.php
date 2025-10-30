<?php

namespace App\Policies;

use App\Models\Proyek;
use App\Models\Pengguna;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view projects list
    }

    public function view(User $user, Project $project): bool
    {
        // Admin and koordinator can view all projects
        if (in_array($user->role, ['admin', 'koordinator'])) {
            return true;
        }

        // Dosen can view their own projects
        if ($user->role === 'dosen' && $project->dosen_id === $user->id) {
            return true;
        }

        // Mahasiswa can view projects they're participating in
        if ($user->role === 'mahasiswa') {
            return $user->groups()->whereHas('project', function ($query) use ($project) {
                $query->where('id', $project->id);
            })->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'dosen']);
    }

    public function update(User $user, Project $project): bool
    {
        // Only project supervisor and admin can update
        return $user->role === 'admin' || 
               ($user->role === 'dosen' && $project->dosen_id === $user->id);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->role === 'admin';
    }

    public function viewReports(User $user, Project $project): bool
    {
        // Admin can view all reports
        if ($user->role === 'admin') {
            return true;
        }

        // Koordinator can view reports in their program
        if ($user->role === 'koordinator' && $project->program_studi === $user->program_studi) {
            return true;
        }

        // Dosen can view reports for their projects
        if ($user->role === 'dosen' && $project->dosen_id === $user->id) {
            return true;
        }

        return false;
    }
}


