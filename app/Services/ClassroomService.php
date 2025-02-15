<?php
namespace App\Services;

use App\Contracts\Classroom;

class ClassroomService
{
    public function __construct(
        protected Classroom $repository
    ) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function create(
        int $id,
        array $data
    )
    {
        return $this->repository->add($id, $data);
    }

    public function update(
        int $id,
        array $data
    )
    {
        $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
    }

    public function getByAcademicYear(int $id)
    {
        return $this->repository->whereHasAcademicYear($id);
    }
}