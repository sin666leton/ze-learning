<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClassroom extends Model
{
    protected $table = 'student_classroom';

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function answerAssignments()
    {
        return $this->hasMany(AnswerAssignment::class);
    }
}
