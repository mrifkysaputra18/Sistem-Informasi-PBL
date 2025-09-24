<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'politala_id',
        'name',
        'email',
        'password',
        'role',
        'phone',
        'program_studi',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function groupsAsLeader()
    {
        return $this->hasMany(Group::class, 'leader_id');
    }

    public function groupMemberships()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_members');
    }

    public function projectsAsSuperviser()
    {
        return $this->hasMany(Project::class, 'dosen_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProgressReview::class, 'reviewer_id');
    }

    // Scopes
    public function scopeMahasiswa($query)
    {
        return $query->where('role', 'mahasiswa');
    }

    public function scopeDosen($query)
    {
        return $query->where('role', 'dosen');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeKoordinator($query)
    {
        return $query->where('role', 'koordinator');
    }

    // Helper methods
    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    public function isDosen()
    {
        return $this->role === 'dosen';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKoordinator()
    {
        return $this->role === 'koordinator';
    }
}