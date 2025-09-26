<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicTerm extends Model
{
    protected $fillable = ['tahun_akademik', 'semester', 'is_active'];
    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
