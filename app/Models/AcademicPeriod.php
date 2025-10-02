<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicPeriod extends Model
{
    use HasFactory;

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
        return $this->hasMany(Subject::class);
    }

    /**
     * Get the classrooms for the academic period.
     */
    public function classrooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
    }

    /**
     * Get the groups through classrooms.
     */
    public function groups()
    {
        return $this->hasManyThrough(Group::class, ClassRoom::class);
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
