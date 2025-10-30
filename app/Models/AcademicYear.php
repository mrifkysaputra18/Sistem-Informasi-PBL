<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
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
     * Get the semesters for the academic year.
     */
    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }

    /**
     * Get the classrooms for the academic year.
     */
    public function classrooms(): HasMany
    {
        return $this->hasMany(RuangKelas::class, 'subject_id');
    }

    /**
     * Scope a query to only include active academic years.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the current active academic year.
     */
    public static function getCurrent()
    {
        return static::active()->first();
    }

    /**
     * Check if this academic year is current.
     */
    public function isCurrent()
    {
        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }
}





