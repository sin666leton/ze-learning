<?php
namespace App\Services;

use App\Contracts\Semester;

class SemesterService
{
    public function __construct(
        protected Semester $semester
    ) {}

    public function all()
    {
        return $this->semester->all();
    }

    public function paginate(int $item = 10)
    {
        return $this->semester->paginate($item);
    }

    public function find(int $id)
    {
        return $this->semester->getByID($id);
    }

    public function create($id, $data)
    {
        return $this->semester->add($id, $data);
    }

    public function update($id, $data)
    {
        $this->semester->update($id, $data);
    }

    public function delete(int $id)
    {
        $this->semester->delete($id);
    }

    public function fromAcademicYear(int $academicYearID)
    {
        return $this->semester->whereHasAcademicYear($academicYearID);
    }
}