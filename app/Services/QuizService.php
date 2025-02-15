<?php
namespace App\Services;

use App\Contracts\Quiz;

class QuizService
{
    public function __construct(
        protected Quiz $repository
    ) {}

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Summary of create
     * @param array{
     *  subject_id: int,
     *  title: string,
     *  content: string,
     *  duration: int,
     *  access_at: string,
     *  ended_at: string
     * } $validatedInput
     */
    public function create(array $validatedInput)
    {
        if (empty($validatedInput['access_at'])) $validatedInput['access_at'] = now();

        $this->repository->add(
            $validatedInput['subject_id'],
            $validatedInput['title'],
            $validatedInput['content'],
            $validatedInput['duration'],
            $validatedInput['access_at'],
            $validatedInput['ended_at']
        );
    }

    /**
     * Summary of update
     * @param int $id
     * @param array{
     *  title: string,
     *  content: string,
     *  duration: int,
     *  access_at: string,
     *  ended_at: string
     * } $validatedInput
     * @return void
     */
    public function update(int $id, array $validatedInput)
    {
        if (empty($validatedInput['access_at'])) $validatedInput['access_at'] = now();

        $this->repository->update(
            $id,
            $validatedInput['title'],
            $validatedInput['content'],
            $validatedInput['duration'],
            $validatedInput['access_at'],
            $validatedInput['ended_at']
        );
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
    }
}