<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'semester_id',
        'name',
        'kkm'
    ];

    public function semester()
    {
        return $this->belongsTo(Semesters::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
