<?php
namespace App\Services;

use App\Contracts\Attempt;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttemptService
{
    public function __construct(
        protected Attempt $repository
    ) {}

    public function create(
        int $studentID,
        int $quizID
    )
    {
        $this->repository->create($studentID, $quizID);
    }

    public function delete(
        int $studentID
    )
    {

        if (!$this->repository->delete($studentID)) {
            throw new ModelNotFoundException("Gagal menyelesaikan kuis", 500);
        }
    }
}