<?php

namespace App\Models;

use App\Traits\StatusAttributeTrait;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use StatusAttributeTrait;

    protected $table = 'quizzes';

    protected $fillable = [
        'title',
        'content',
        'duration',
        'access_at',
        'ended_at'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function scores()
    {
        return $this->morphMany(Score::class, 'scoreable');
    }

}
