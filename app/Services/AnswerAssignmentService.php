<?php
namespace App\Services;

use App\Contracts\AnswerAssignment;

class AnswerAssignmentService
{
    public function __construct(
        protected AnswerAssignment $repository
    ) {}

    public function studentAnswer(int $studentID)
    {
        return $this->repository->findStudent($studentID);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Summary of create
     * @param array{
     *  assignment_id: int,
     *  student_classroom_id: int,
     *  link: string,
     *  namespace: string
     * } $validatedInput
     * @return void
     */
    public function create(array $validatedInput)
    {
        $this->repository->add(
            $validatedInput['student_classroom_id'],
            $validatedInput['assignment_id'],
            $validatedInput['namespace'],
            $validatedInput['link']
        );
    }
}