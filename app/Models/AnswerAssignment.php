<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerAssignment extends Model
{
    protected $table = 'answer_assignments';

    protected $fillable = [
        'student_classroom_id',
        'link',
        'namespace'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_classroom_id', 'id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'student_classroom_id', 'id');
    }

    public function studentClassroom()
    {
        return $this->belongsTo(StudentClassroom::class);
    }

    public function score()
    {
        return $this->morphOne(Score::class, 'scoreable');
    }
}
