<?php
namespace App\Services;

use App\Contracts\Assignment;

class AssignmentService
{
    public function __construct(
        protected Assignment $repository
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
     *  access_at: string|null,
     *  ended_at: string,
     *  size: int
     * } $validatedInput
     */
    public function create(array $validatedInput)
    {
        $access_at = empty($validatedInput['access_at']) ? now() : $validatedInput['access_at'];

        $this->repository->add(
            $validatedInput['subject_id'],
            $validatedInput['title'],
            $validatedInput['content'],
            $access_at,
            $validatedInput['ended_at'],
            $validatedInput['size']
        );
    }

    /**
     * Summary of update
     * @param array{
     *  title: string,
     *  content: string,
     *  access_at: string,
     *  ended_at: string,
     *  size: int
     * } $validatedInput
     */
    public function update(int $id, array $validatedInput)
    {
        $access_at = empty($validatedInput['access_at']) ? now() : $validatedInput['access_at'];

        $this->repository->update(
            $id,
            $validatedInput['title'],
            $validatedInput['content'],
            $access_at,
            $validatedInput['ended_at'],
            $validatedInput['size']
        );
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
    }
}