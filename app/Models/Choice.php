<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    protected $table = 'choices';

    protected $fillable = [
        'content'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
