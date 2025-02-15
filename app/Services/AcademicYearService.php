<?php
namespace App\Services;

use App\Contracts\AcademicYear;

class AcademicYearService
{
    public function __construct(
        protected AcademicYear $academicYear
    ) {}

    public function all()
    {
        return $this->academicYear->all();
    }

    public function paginate(int $item = 10)
    {
        return $this->academicYear->paginate($item);
    }

    public function find(int $id)
    {
        return $this->academicYear->find($id);
    }

    public function create(string $name)
    {
        return $this->academicYear->add($name);
    }

    public function update(int $id, string $name)
    {
        $this->academicYear->update(
            $id,
            $name
        );
    }

    public function delete(int $id)
    {
        $this->academicYear->delete($id);
    }

    public function latest()
    {
        return $this->academicYear->latest();
    }
}