<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';

    protected $fillable = [
        'content',
        'point',
        'type'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }

    public function answerKey()
    {
        return $this->hasOne(AnswerKey::class);
    }

    public function answerQuestion()
    {
        return $this->hasMany(AnswerQuestion::class);
    }
}
