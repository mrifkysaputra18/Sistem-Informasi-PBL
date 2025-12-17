<?php

namespace App\Policies;

use App\Models\TargetMingguan;
use App\Models\Pengguna;
use App\Models\RuangKelas;

/**
 * Policy untuk authorization Target Mingguan
 * Menggantikan duplikasi authorization checks di controller
 */
class TargetMingguanPolicy
{
    /**
     * Determine apakah user bisa melihat daftar targets
     * Admin & Koordinator: semua
     * Dosen: hanya kelas yang diampu
     * Mahasiswa: hanya target kelompoknya
     */
    public function viewAny(Pengguna $user): bool
    {
        return in_array($user->role, ['admin', 'koordinator', 'dosen', 'mahasiswa']);
    }

    /**
     * Determine apakah user bisa melihat target spesifik
     */
    public function view(Pengguna $user, TargetMingguan $target): bool
    {
        // Admin, koordinator, dan dosen bisa lihat semua
        if (in_array($user->role, ['admin', 'koordinator', 'dosen'])) {
            return true;
        }

        // Mahasiswa hanya bisa lihat target kelompoknya
        if ($user->isMahasiswa()) {
            return $target->group->members()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine apakah user bisa membuat target
     * Hanya admin dan dosen
     */
    public function create(Pengguna $user): bool
    {
        return in_array($user->role, ['admin', 'dosen']);
    }

    /**
     * Determine apakah user bisa membuat target untuk kelas tertentu
     */
    public function createForClassRoom(Pengguna $user, int $classRoomId): bool
    {
        // Admin dan dosen bisa buat target untuk kelas manapun
        return in_array($user->role, ['admin', 'dosen']);
    }

    /**
     * Determine apakah user bisa update target
     * Admin, dosen, atau creator bisa update
     */
    public function update(Pengguna $user, TargetMingguan $target): bool
    {
        // Admin dan dosen bisa update semua target
        if (in_array($user->role, ['admin', 'dosen'])) {
            return true;
        }

        // Creator bisa update targetnya sendiri
        if ($target->created_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine apakah user bisa delete target
     * Sama seperti update
     */
    public function delete(Pengguna $user, TargetMingguan $target): bool
    {
        return $this->update($user, $target);
    }

    /**
     * Determine apakah user bisa force delete target (bypass checks)
     * Hanya admin
     */
    public function forceDelete(Pengguna $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine apakah user bisa manage targets per minggu
     * (bulk update, delete, reopen, close)
     */
    public function manageWeek(Pengguna $user, int $classRoomId): bool
    {
        // Admin, koordinator, dan dosen bisa manage semua kelas
        return in_array($user->role, ['admin', 'koordinator', 'dosen']);
    }

    /**
     * Determine apakah user bisa reopen/close target
     */
    public function toggleStatus(Pengguna $user, TargetMingguan $target): bool
    {
        // Admin, koordinator, dan dosen bisa toggle status semua target
        return in_array($user->role, ['admin', 'koordinator', 'dosen']);
    }

    /**
     * Determine apakah user bisa trigger auto-close
     */
    public function autoClose(Pengguna $user): bool
    {
        return in_array($user->role, ['admin', 'koordinator', 'dosen']);
    }

    /**
     * Determine apakah mahasiswa bisa submit target
     */
    public function submit(Pengguna $user, TargetMingguan $target): bool
    {
        if (!$user->isMahasiswa()) {
            return false;
        }

        // Mahasiswa harus anggota kelompok
        if (!$target->group->members()->where('user_id', $user->id)->exists()) {
            return false;
        }

        // Target harus masih bisa menerima submission
        return $target->canAcceptSubmission();
    }

    /**
     * Determine apakah user bisa download file dari target
     */
    public function download(Pengguna $user, TargetMingguan $target): bool
    {
        // Admin, dosen, koordinator bisa download semua
        if (in_array($user->role, ['admin', 'koordinator', 'dosen'])) {
            return true;
        }

        // Mahasiswa hanya bisa download file kelompoknya
        if ($user->isMahasiswa()) {
            return $target->group->members()->where('user_id', $user->id)->exists();
        }

        return false;
    }
}
