<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['kode', 'nama', 'academic_term_id', 'judul_proyek'];
    public function term()
    {
        return $this->belongsTo(AcademicTerm::class, 'academic_term_id');
    }
    public function scores()
    {
        return $this->hasMany(GroupScore::class);
    }
}
