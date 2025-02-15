<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        if (!$user->profile) {
            $user->profile = "/assets/images/default.jpg";
        }
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        session()->flash('success', "Pelajar 123 telah dihapus");
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
