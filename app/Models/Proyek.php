<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    protected $table = 'proyek';

    protected $fillable = [
        'title',
        'description',
        'dosen_id',
        'program_studi',
        'start_date',
        'end_date',
        'max_members',
        'status',
        'rubrik_penilaian',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'rubrik_penilaian' => 'array',
        ];
    }

    // Relationships
    public function supervisor()
    {
        return $this->belongsTo(Pengguna::class, 'dosen_id');
    }

    public function groups()
    {
        return $this->hasMany(Kelompok::class, 'project_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function getCurrentWeek()
    {
        $startDate = $this->start_date;
        $now = now();
        
        if ($now < $startDate) {
            return 0;
        }
        
        return $startDate->diffInWeeks($now) + 1;
    }

    public function getTotalWeeks()
    {
        return $this->start_date->diffInWeeks($this->end_date) + 1;
    }
}





