<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $table = 'scores';

    protected $fillable = [
        'student_classroom_id',
        'scoreable_id',
        'scoreable_type',
        'point',
        'published'
    ];

    public function studentClassroom()
    {
        return $this->belongsTo(StudentClassroom::class);
    }

    public function scoreable()
    {
        return $this->morphTo('scoreable');
    }
}
