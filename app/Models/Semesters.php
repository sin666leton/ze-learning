<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semesters extends Model
{
    protected $table = 'semesters';

    protected $fillable = [
        'name',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
