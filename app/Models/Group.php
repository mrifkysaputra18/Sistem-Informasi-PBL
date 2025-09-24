<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_id',
        'leader_id',
        'google_drive_folder_id',
        'total_score',
        'ranking',
    ];

    protected function casts(): array
    {
        return [
            'total_score' => 'decimal:2',
        ];
    }

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members');
    }

    public function groupMembers()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function weeklyProgress()
    {
        return $this->hasMany(WeeklyProgress::class);
    }

    // Helper methods
    public function getMembersCount()
    {
        return $this->members()->count();
    }

    public function getAverageScore()
    {
        return $this->weeklyProgress()
            ->whereHas('review')
            ->with('review')
            ->get()
            ->avg('review.total_score') ?? 0;
    }
}