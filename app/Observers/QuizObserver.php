<?php

namespace App\Observers;

use App\Models\Quiz;

class QuizObserver
{
    /**
     * Handle the Quiz "created" event.
     */
    public function created(Quiz $quiz): void
    {
        session()->flash('success', 'Kuis telah ditambahkan');
    }

    /**
     * Handle the Quiz "updated" event.
     */
    public function updated(Quiz $quiz): void
    {
        session()->flash('success', 'Kuis telah diperbarui');
    }

    /**
     * Handle the Quiz "deleted" event.
     */
    public function deleted(Quiz $quiz): void
    {
        session()->flash('success', 'Kuis telah dihapus');
    }

    /**
     * Handle the Quiz "restored" event.
     */
    public function restored(Quiz $quiz): void
    {
        //
    }

    /**
     * Handle the Quiz "force deleted" event.
     */
    public function forceDeleted(Quiz $quiz): void
    {
        //
    }
}
