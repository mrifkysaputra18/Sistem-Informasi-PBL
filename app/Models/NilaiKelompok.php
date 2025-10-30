<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiKelompok extends Model
{
    protected $table = 'nilai_kelompok';

    protected $fillable = ['group_id', 'criterion_id', 'skor'];
    public function group()
    {
        return $this->belongsTo(Kelompok::class, 'group_id');
    }
    public function criterion()
    {
        return $this->belongsTo(Kriteria::class, 'criterion_id');
    }
}







