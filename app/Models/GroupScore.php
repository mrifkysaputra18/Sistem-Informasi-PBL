<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupScore extends Model
{
    protected $fillable = ['group_id', 'criterion_id', 'skor'];
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }
}
