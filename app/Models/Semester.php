<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'number',
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
     * Get the academic year that owns the semester.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the classrooms for the semester.
     */
    public function classrooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
    }

    /**
     * Scope a query to only include active semesters.
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
        return $query->whereIn('number', [3, 4, 5]);
    }

    /**
     * Get the current active semester.
     */
    public static function getCurrent()
    {
        return static::active()->first();
    }

    /**
     * Check if this semester is current.
     */
    public function isCurrent()
    {
        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }

    /**
     * Check if this semester is PBL related.
     */
    public function isPbl()
    {
        return in_array($this->number, [3, 4, 5]);
    }
}