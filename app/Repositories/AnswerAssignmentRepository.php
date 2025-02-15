<?php
namespace App\Repositories;

use App\Models\AnswerAssignment;
use App\Models\Assignment;

class AnswerAssignmentRepository implements \App\Contracts\AnswerAssignment
{
    public function findStudent(int $id)
    {
        return AnswerAssignment::where('student_classroom_id', $id)->first();
    }

    public function find(int $id)
    {
        return AnswerAssignment::findOrFail($id);
    }

    public function add(
        int $student_classroom_id,
        int $assignment_id,
        string $namespace,
        string $link
    )
    {
        $answer = Assignment::findOrFail($assignment_id)->answerAssignments()->create([
            'student_classroom_id' => $student_classroom_id,
            'namespace' => $namespace,
            'link' => $link
        ]);

        $answer->scores()->create([
            'student_classroom_id' => $student_classroom_id,
            'point' => 0
        ]);
    }

    public function delete(int $id)
    {
        $this->find($id)->delete();
    }

    public function createScore()
    {

    }
}
