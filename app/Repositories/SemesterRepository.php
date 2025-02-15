<?php
namespace App\Repositories;

use App\Models\AcademicYear;
use App\Models\Semesters;

class SemesterRepository implements \App\Contracts\Semester
{
    public function all()
    {
        return Semesters::all();
    }

    public function paginate(int $item = 10)
    {
        return Semesters::paginate($item);
    }

    public function getByID(int $id)
    {
        return Semesters::findOrFail($id);
        
    }

    public function add(int $academicYearID, array $data)
    {
        return AcademicYear::findOrFail($academicYearID)->semesters()->create($data);
    }

    public function update(int $id, array $data)
    {
        Semesters::findOrFail($id)->update($data);
    }

    public function delete(int $id)
    {
        Semesters::findOrFail($id)->delete();
    }

    public function whereHasAcademicYear(int $academicYear)
    {
        return Semesters::where('academic_year_id', $academicYear)
            ->get();
    }
}