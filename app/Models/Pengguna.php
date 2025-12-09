<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'politala_id',
        'nim',
        'name',
        'email',
        'password',
        'role',
        'phone',
        'program_studi',
        'class_room_id',
        'is_active',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
    
    /**
     * Get user's classroom
     */
    public function classRoom()
    {
        return $this->belongsTo(RuangKelas::class, 'class_room_id');
    }

    /**
     * Get academic period through classroom
     */
    public function academicPeriod()
    {
        return $this->hasOneThrough(
            PeriodeAkademik::class,
            RuangKelas::class,
            'id',                  // FK di class_rooms
            'id',                  // FK di academic_periods
            'class_room_id',       // Local key di users
            'academic_period_id'   // Local key di class_rooms
        );
    }
    
    /**
     * Get user's group memberships
     */
    public function groupMembers()
    {
        return $this->hasMany(AnggotaKelompok::class, 'user_id');
    }
    
    /**
     * Get user's groups
     */
    public function groups()
    {
        return $this->belongsToMany(Kelompok::class, 'anggota_kelompok', 'user_id', 'group_id')->withTimestamps();
    }
    
    /**
     * Get groups where user is leader
     */
    public function ledGroups()
    {
        return $this->hasMany(Kelompok::class, 'leader_id');
    }

    /**
     * Get user's current group (first active group)
     */
    public function currentGroup()
    {
        return $this->groups()->first();
    }

    /**
     * Check if user has group in current academic period
     */
    public function hasGroupInCurrentPeriod()
    {
        $currentPeriod = AcademicPeriod::getCurrent();
        if (!$currentPeriod) return false;

        return $this->groups()
            ->whereHas('classRoom', function($query) use ($currentPeriod) {
                $query->where('academic_period_id', $currentPeriod->id);
            })
            ->exists();
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    /**
     * Check if user is coordinator
     */
    public function isKoordinator(): bool
    {
        return $this->role === 'koordinator';
    }
    
    /**
     * Check if user is lecturer
     */
    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }
    
    /**
     * Check if user is student
     */
    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }
    
    /**
     * Get student scores
     */
    public function studentScores()
    {
        return $this->hasMany(NilaiMahasiswa::class, 'user_id');
    }

    /**
     * Get mata kuliah for dosen
     */
    public function mataKuliahs()
    {
        return $this->belongsToMany(MataKuliah::class, 'dosen_mata_kuliah', 'dosen_id', 'mata_kuliah_id')
                    ->withTimestamps();
    }

    /**
     * Get rubrik penilaian created by this dosen
     */
    public function rubrikPenilaians()
    {
        return $this->hasMany(RubrikPenilaian::class, 'created_by');
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo_path 
            ? asset('storage/' . $this->profile_photo_path)
            : $this->getDefaultProfilePhotoUrl();
    }

    /**
     * Get default profile photo URL based on role
     */
    public function getDefaultProfilePhotoUrl(): string
    {
        // Return default avatar based on role or user's initial
        $initial = strtoupper(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&color=0056b3&background=e6f0ff&size=200&bold=true";
    }
}