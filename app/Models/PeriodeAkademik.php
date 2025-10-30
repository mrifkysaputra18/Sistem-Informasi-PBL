<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeAkademik extends Model
{
    use HasFactory;

    protected $table = 'periode_akademik';

    protected $fillable = [
        'name',
        'code',
        'academic_year',
        'semester_number',
        'start_date',
        'end_date',
        'is_active',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the subjects for the academic period.
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(MataKuliah::class, 'academic_period_id');
    }

    /**
     * Get the classrooms for the academic period.
     */
    public function classrooms(): HasMany
    {
        return $this->hasMany(RuangKelas::class, 'subject_id');
    }

    /**
     * Get the groups through classrooms.
     */
    public function groups()
    {
        return $this->hasManyThrough(Kelompok::class, RuangKelas::class);
    }

    /**
     * Get all students in this academic period (through classrooms)
     */
    public function students()
    {
        return $this->hasManyThrough(Pengguna::class, RuangKelas::class, 'academic_period_id', 'class_room_id')
                    ->where('users.role', 'mahasiswa');
    }

    /**
     * Get all group scores in this period
     */
    public function groupScores()
    {
        return $this->hasManyThrough(
            NilaiKelompok::class,
            Kelompok::class,
            'class_room_id', // FK di groups ke class_rooms
            'group_id',      // FK di group_scores ke groups
            'id',            // PK di academic_periods
            'id'             // PK di groups
        )->whereHas('group.classRoom', function($query) {
            $query->where('academic_period_id', $this->id);
        });
    }

    /**
     * Get all weekly progress in this period
     */
    public function weeklyProgress()
    {
        return $this->hasManyThrough(
            KemajuanMingguan::class,
            Kelompok::class,
            'class_room_id',
            'group_id'
        )->whereHas('group.classRoom', function($query) {
            $query->where('academic_period_id', $this->id);
        });
    }

    /**
     * Get total students count
     */
    public function getTotalStudentsAttribute()
    {
        return $this->students()->count();
    }

    /**
     * Get total groups count
     */
    public function getTotalGroupsAttribute()
    {
        return $this->groups()->count();
    }

    /**
     * Get total classes count
     */
    public function getTotalClassesAttribute()
    {
        return $this->classrooms()->count();
    }

    /**
     * Scope a query to only include active academic periods.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include PBL semesters (3, 4, 5).
     */
    public function scopePbl($query)
    {
        return $query->whereIn('semester_number', [3, 4, 5]);
    }

    /**
     * Get the current active academic period.
     */
    public static function getCurrent()
    {
        return static::active()->first();
    }

    /**
     * Check if this period is current.
     */
    public function isCurrent()
    {
        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }

    /**
     * Check if this period is PBL related.
     */
    public function isPbl()
    {
        return in_array($this->semester_number, [3, 4, 5]);
    }

    /**
     * Get formatted display name.
     */
    public function getDisplayNameAttribute()
    {
        return "{$this->name} (Semester {$this->semester_number})";
    }
}







