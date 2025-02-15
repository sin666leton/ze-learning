<?php

namespace App\Contracts;

interface Attempt
{
    public function get(
        int $studentID
    );
    public function create(
        int $studentID,
        int $quizID
    );

    public function delete(int $id);

    public function exists(
        int $studentID
    );

    public function createScoreFromAttempt(
        \App\Models\Attempt $attempt,
        int $studentClassroomID
    );
}
