<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    protected $fillable = ['nama', 'bobot', 'tipe', 'segment'];
    public function groupScores()
    {
        return $this->hasMany(GroupScore::class, 'criterion_id');
    }
}
