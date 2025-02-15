<?php

namespace App\Observers;

use App\Models\Assignment;

class AssignmentObserver
{
    public function creating(Assignment $assignment)
    {
        if ($assignment->access_at == null) $assignment->access_at = now();
    }
    
    /**
     * Handle the Assignment "created" event.
     */
    public function created(Assignment $assignment): void
    {
        session()->flash('success', 'Tugas telah ditambahkan');
    }

    /**
     * Handle the Assignment "updated" event.
     */
    public function updated(Assignment $assignment): void
    {
        session()->flash('success', 'Tugas telah diperbarui');
    }

    /**
     * Handle the Assignment "deleted" event.
     */
    public function deleted(Assignment $assignment): void
    {
        session()->flash('success', 'Tugas telah dihapus');
    }

    /**
     * Handle the Assignment "restored" event.
     */
    public function restored(Assignment $assignment): void
    {
        //
    }

    /**
     * Handle the Assignment "force deleted" event.
     */
    public function forceDeleted(Assignment $assignment): void
    {
        //
    }
}
