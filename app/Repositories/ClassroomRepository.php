<?php
namespace App\Repositories;

use App\Models\AcademicYear;
use App\Models\Classroom;

class ClassroomRepository implements \App\Contracts\Classroom
{
    public function all()
    {
        return Classroom::all();
    }

    public function find(int $id)
    {
        return Classroom::findOrFail($id);
    }

    public function withAcademicYearAndSemesterFind(int $id): Classroom
    {
        return Classroom::with('academicYear.semesters')
            ->findOrFail($id);
    }

    public function add(int $academicYearID, array $data)
    {
        return AcademicYear::findOrFail($academicYearID)
            ->classrooms()
            ->create($data);
    }

    public function update(int $id, array $data)
    {
        Classroom::findOrFail($id)->update($data);
    }

    public function delete(int $id)
    {
        Classroom::findOrFail($id)->delete();
    }

    public function whereHasAcademicYear(int $id)
    {
        return Classroom::whereHas(
                'academicYear',
                function ($query) use ($id) {
                    $query->where('id', $id);
                }
            )
            ->get();
    }
}