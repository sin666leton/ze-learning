<?php
namespace App\Repositories\V1;

use App\Exceptions\AcademicYearNotExists;
use App\Exceptions\ClassroomNotExists;
use App\Models\AcademicYear;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Collection;

class ClassroomRepository implements \App\Contracts\Classroom
{
    public function find(int $id, string $relation = 'subject', int|null $semesterID = null)
    {
        if ($semesterID == null);

        $classroom = Classroom::with([
            'academicYear' => function ($academic) {
                $academic->with([
                    'semesters' => function ($query) {
                        $query->select(['id', 'academic_year_id', 'name']);
                    }
                ]);
            }
        ])
        ->when(
            $relation == "student",
            function ($query) {
                $query->with([
                    'students' => function ($students) {
                        $students->select(['nis', 'user_id', 'student_id'])
                            ->with([
                                'user' => function ($user) {
                                    $user->select(['id', 'name', 'email']);
                                }
                            ]);
                    }
                ]);
            })
            ->select(['id', 'name', 'academic_year_id'])
            ->withCount(['students', 'subjects'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new ClassroomNotExists();
            });

        if ($relation == 'subject') {
            if ($semesterID == null) $semesterID = $classroom->academicYear->semesters->last()->id;

            $classroom->load([
                'subjects' => function ($subject) use ($semesterID) {
                    $subject->select(['name', 'id', 'classroom_id', 'semester_id', 'kkm'])
                        ->where('semester_id', $semesterID);
                }
            ]);
        }

        return $classroom->toArray();
    }

    // public function 

    public function loadFromCollection(Collection $academicYear): array
    {
        $academicYear->load([
            'classrooms' => function ($query) {
                $query->select(['id', 'academic_year_id', 'name'])
                    ->withCount('students');
            }
        ]);

        $academicYear = $academicYear->first();
        
        if ($academicYear == null) throw new AcademicYearNotExists();

        return $academicYear->classrooms->toArray();
    }

    public function create(int $academicYearID, string $name): array
    {
        $academicYear = AcademicYear::select('id')
            ->where('id', $academicYearID)
            ->firstOr(function () {
                throw new AcademicYearNotExists();
            });

        $classroom = $academicYear->classrooms()
            ->create([
                'name' => $name
            ]);

        return $classroom->only(['academic_year_id']);
    }

    public function update(int $id, string $name): array
    {
        $classroom = Classroom::select(['id', 'name'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new ClassroomNotExists();
            });

        $classroom->update([
            'name' => $name
        ]);

        return $classroom->toArray();
    }

    public function delete(int $id): bool|null
    {
        $classroom = Classroom::select(['id', 'name'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new ClassroomNotExists();
            });

        return $classroom->delete();
    }
}