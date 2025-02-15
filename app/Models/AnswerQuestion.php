<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerQuestion extends Model
{
    protected $table = 'answer_questions';

    protected $fillable = [
        'student_id',
        'content',
        'is_correct'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
