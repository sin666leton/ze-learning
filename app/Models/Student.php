<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'nis'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(
            Classroom::class,
            'student_classroom',
            'student_id',
            'classroom_id'
        )->withPivot('id');
    }

    public function attempt()
    {
        return $this->hasOne(Attempt::class);
    }

    public function answerAssignments()
    {
        return $this->hasMany(AnswerAssignment::class);
    }

    public function answerQuestions()
    {
        return $this->hasMany(AnswerQuestion::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'student_classroom_id');
    }
}
