<?php

namespace App\Observers;

class LogAlertEntityObserver
{
    public function created($entity)
    {
        session()->flash("success", "$entity->name telah ditambahkan!");
    }
    
    public function updated($entity)
    {
        session()->flash("success", "$entity->name telah diperbarui!");
    }

    public function deleted($entity)
    {
        session()->flash("success", "$entity->name telah dihapus!");
    }
}
