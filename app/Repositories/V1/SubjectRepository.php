<?php
namespace App\Repositories\V1;

use App\Exceptions\AcademicYearNotExists;
use App\Exceptions\ClassroomNotExists;
use App\Exceptions\SemesterNotExists;
use App\Exceptions\SubjectNotExists;
use App\Models\Classroom;
use App\Models\Subject;

class SubjectRepository implements \App\Contracts\Subject
{
    public function find(int $id): array
    {
        $subject = Subject::with([
            'semester' => function ($semester) {
                $semester->select(['id', 'name']);
            },
            'classroom' => function ($classroom) {
                $classroom->select(['id', 'name']);
            }
        ])
        ->select(['id', 'semester_id', 'classroom_id', 'name', 'kkm'])
        ->where('id', $id)
        ->firstOr(function () {
            throw new SubjectNotExists();
        });

        return $subject->toArray();
    }

    public function getByClassroom(int $id): array
    {
        $classroom = Classroom::with([
            'academicYear' => function ($academic) {
                $academic->select(['id', 'name'])
                    ->with([
                        'semesters' => function ($semesters) {
                            $semesters->select(['academic_year_id', 'id', 'name']);
                        }
                    ]);
            }
        ])
        ->select(['id', 'name'])
        ->where('id', $id)
        ->get();

        return $classroom->toArray();
    }

    public function findSubjectIncludeSemesterList(int $id): array
    {
        $subject = Subject::with([
            'semester' => function ($semester) {
                $semester->select(['id', 'name']);
            },
            'classroom' => function ($classroom) {
                $classroom->select(['id', 'academic_year_id', 'name'])
                    ->with([
                        'academicYear' => function ($academic) {
                            $academic->select('id')
                                ->with([
                                    'semesters' => function ($semesters) {
                                        $semesters->select(['academic_year_id', 'id', 'name']);
                                    }
                                ]);
                        }
                    ]);
            }
        ])
        ->select(['id', 'name', 'classroom_id', 'semester_id', 'kkm'])
        ->where('id', $id)
        ->firstOr(function () {
            throw new SubjectNotExists();
        });

        return $subject->toArray();
    }

    public function create(int $semesterID, int $classroomID, string $name, int $kkm = 70): array
    {
        $classroom = Classroom::with([
            'academicYear' => function ($academic) use (&$semesterID) {
                $academic->select(['id'])
                    ->withWhereHas('semesters', function ($semesters) use (&$semesterID) {
                        $semesters->select(['id', 'academic_year_id'])
                            ->where('id', $semesterID)
                            ->limit(1);
                    });
            }
        ])
        ->select(['id', 'academic_year_id'])
        ->where('id', $classroomID)
        ->firstOr(function () {
            throw new ClassroomNotExists();
        });

        if ($classroom->academicYear == null) throw new AcademicYearNotExists();

        $subject = $classroom->subjects()
            ->create([
                'semester_id' => $semesterID,
                'name' => $name,
                'kkm' => $kkm
            ]);

        return $subject->only(['id', 'classroom_id', 'semester_id']);
    }

    public function update(int $semesterID, int $id, string $name, int $kkm = 70): array
    {
        $subject = Subject::with([
            'semester' => function ($semester) use (&$semesterID) {
                $semester->select(['id', 'academic_year_id'])
                    ->with([
                        'academicYear' => function ($academic) use (&$semesterID) {
                            $academic->select(['id'])
                                ->withWhereHas('semesters', function ($semesters) use (&$semesterID) {
                                    $semesters->select(['academic_year_id', 'id'])
                                        ->where('id', $semesterID)
                                        ->limit(1);
                                    }
                                );
                        }
                    ]);
            }
        ])
        ->select(['id', 'classroom_id', 'semester_id', 'name', 'kkm'])
        ->where('id', $id)
        ->firstOr(function() {
            throw new SubjectNotExists();
        });

        if ($subject->semester->academicYear == null) throw new SemesterNotExists();

        $subject->update([
            'semester_id' => $semesterID,
            'name' => $name,
            'kkm' => $kkm
        ]);

        return $subject->only(['classroom_id', 'semester_id']);
    }

    public function delete(int $id): bool|null
    {
        $subject = Subject::select(['id', 'name'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new SubjectNotExists();
            });

        return $subject->delete();
    }
}
