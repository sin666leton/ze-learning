<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = "classrooms";

    protected $fillable = [
        'name'
    ];
    
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'student_classroom',
            'classroom_id',
            'student_id'
        )->withPivot('id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'student_classroom_id');
    }
}
