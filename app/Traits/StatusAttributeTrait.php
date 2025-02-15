<?php

namespace App\Traits;

trait StatusAttributeTrait
{
    public function getStatusAttribute()
    {
        if (now() > $this->ended_at) {
            return 'Selesai';
        } else {
            return $this->access_at > now() ? 'Dikunci' : 'Dibuka';
        }
    }
}
