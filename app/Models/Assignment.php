<?php

namespace App\Models;

use App\Traits\StatusAttributeTrait;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use StatusAttributeTrait;

    protected $table = 'assignments';

    protected $fillable = [
        'title',
        'content',
        'access_at',
        'ended_at',
        'size'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function answerAssignments()
    {
        return $this->hasMany(AnswerAssignment::class);
    }
}
