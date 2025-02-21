<?php
namespace App\Repositories\V1;

use App\Exceptions\ClassroomNotExists;
use App\Exceptions\StudentNotExists;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentRepository implements \App\Contracts\Student
{  
    public function paginate(int $item = 10)
    {
        return User::withWhereHas('student', function ($query) {
            $query->select(['id', 'user_id', 'nis']);
        })
        ->select(['id', 'name', 'email', 'created_at'])
        ->paginate($item);
    }

    public function find(int $id)
    {
        $student = Student::with([
            'user' => function ($query) {
                $query->select(['id', 'name', 'email', 'created_at', 'profile']);
            },
            'classrooms' => function ($query) {
                $query->select(['name', 'academic_year_id'])
                    ->latest('student_classroom.id')
                    ->with([
                        'academicYear' => function ($academic) {
                            $academic->select(['id', 'name']);
                        }
                    ]);
            }
        ])
        ->where('id', $id)
        ->select(['id', 'user_id', 'nis'])
        ->firstOr(function () {
            throw new StudentNotExists();
        });

        return $student->toArray();
    }

    public function findByNISandClassroom(int $nis, int $classroomID)
    {
        $data = Student::with([
            'user' => function ($user) {
                $user->select(['id', 'name']);
            }
        ])
        ->whereDoesntHave('classrooms', function ($query) use ($classroomID) {
            $query->where('classroom_id', $classroomID);
        })
        ->select('id', 'student_id', 'nis')
        ->where('nis', $nis)
        ->firstOr(function () {
            throw new StudentNotExists();
        });

        return $data->toArray();
    }

    public function create(string $email, string $name, string $password, int $nis)
    {
        $user = User::create([
            'email' => $email,
            'name' => $name,
            'password' => Hash::make($password)
        ]);

        $user->student()->create([
            'nis' => $nis
        ]);
    }

    public function update(int $id, string $email, string $name, int $nis)
    {
        $student = Student::with([
            'user' => function ($user) {
                $user->select(['id', 'name', 'email']);
            }
        ])
        ->select(['user_id', 'nis'])
        ->where('id', $id)
        ->firstOr(function () {
            throw new StudentNotExists();
        });

        $student->user->update([
            'email' => $email,
            'name' => $name
        ]);

        $student->update([
            'nis' => $nis
        ]);
    }

    public function delete(int $id): bool
    {
        $query = Student::with(['user' => function ($query) {
            $query->select(['id']);
        }])
        ->select(['id', 'user_id'])
        ->findOr($id, function () {
            throw new StudentNotExists();
        });

        return $query->user->delete();
    }

    public function attachClassroom(int $studentID, int $classroomID)
    {
        $classroom = Classroom::select(['id'])
            ->findOr($classroomID, function () {
                throw new ClassroomNotExists();
            });

        $student = Student::select(['id'])
            ->findOr($studentID, function () {
                throw new StudentNotExists();
            });

        $student->classrooms()->attach($classroom->id);

    }

    public function detachClassroom(int $studentID, int $classroomID)
    {
        $pivot = StudentClassroom::select(['id', 'classroom_id', 'student_id'])
            ->where('student_id', $studentID)
            ->where('classroom_id', $classroomID)
            ->firstOr(function () {
            });

        $pivot->delete();
    }

    public function getLastAttachClassroom(int $studentID)
    {
        $query = Student::with(['classrooms' => function ($classroom) {
            $classroom->select('classrooms.id', 'classrooms.name')
            ->latest('student_classroom.id')
            ->limit(1);
        }])
        ->select(['id'])
        ->findOr($studentID, function () {
            throw new StudentNotExists();
        });

        return $query->classrooms->first();
    }
}