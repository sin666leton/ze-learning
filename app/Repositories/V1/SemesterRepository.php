<?php
namespace App\Repositories\V1;

use App\Exceptions\AcademicYearNotExists;
use App\Exceptions\SemesterNotExists;
use App\Models\AcademicYear;
use App\Models\Semesters;

class SemesterRepository implements \App\Contracts\Semester
{
    public function find(int $id): array
    {
        $semester = Semesters::with([
            'academicYear' => function ($query) {
                $query->select(['id', 'name']);
            }
        ])
        ->select(['id', 'academic_year_id', 'name'])
        ->where('id', $id)
        ->firstOr(function () {
            throw new SemesterNotExists();
        });

        return $semester->toArray();
    }

    public function getFromAcademicYear(int $academicYearID): array
    {
        
        return [];
    }

    public function loadFromCollection(\Illuminate\Database\Eloquent\Collection $academicYear): array
    {
        $semesters = $academicYear->load([
            'semesters' => function ($query) {
                $query->select(['id', 'academic_year_id', 'name']);
            }
        ]);

        return $semesters = $semesters->first()->semesters->toArray();
    }

    public function create(int $academicYearID, string $name): array
    {
        $semester = AcademicYear::select('id')
            ->where('id', $$academicYearID)
            ->firstOr(function () {
                throw new AcademicYearNotExists();
            })
            ->semesters()
            ->create([
                'name' => $name
            ]);

        return $semester->only(['id', 'academic_year_id', 'name']);
    }

    public function update(int $id, string $name): array
    {
        $semester = Semesters::select(['id', 'name', 'academic_year_id'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new SemesterNotExists();
            });
        
        $semester->update([
            'name' => $name
        ]);

        return $semester->only(['id', 'academic_year_id', 'name']);
    }

    public function delete(int $id): bool|null
    {
        $semester = Semesters::select(['id', 'name'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new SemesterNotExists();
            });
            
        return $semester->delete();
    }
}