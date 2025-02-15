<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $table = 'academic_years';

    protected $fillable = [
        'name'
    ];

    public function semesters()
    {
        return $this->hasMany(Semesters::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
