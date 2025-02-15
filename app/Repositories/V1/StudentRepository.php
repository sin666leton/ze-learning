<?php
namespace App\Repositories\V1;

use App\Exceptions\ClassroomNotExists;
use App\Exceptions\StudentNotExists;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentRepository implements \App\Contracts\Student
{
    public function all()
    {
        $query = Student::with(['user' => function ($query) {
            $query->select(['id', 'name', 'email']);
        }])
        ->select(['id', 'user_id', 'nis'])
        ->get();

        return $query;
    }

    public function paginate(int $item = 10)
    {
        return Student::with(['user' => function ($query) {
            $query->select(['id', 'name', 'email']);
        }])
        ->select(['id', 'user_id', 'nis'])
        ->paginate($item);
    }

    public function find(int $id)
    {
        $query = Student::with(['user' => function ($query) {
            $query->select(['id', 'name', 'email', 'created_at']);
        }])
        ->select(['id', 'user_id', 'nis'])
        ->findOr($id, function () {
            throw new StudentNotExists();
        });

        return $query;
    }

    public function update(int $id, string $email, string $name, int $nis)
    {
        $student = $this->find($id);

        $student->user->update([
            'email' => $email,
            'name' => $name
        ]);

        $student->update([
            'nis' => $nis
        ]);
    }

    public function add(string $email, string $name, string $password, int $nis)
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
        $student = Student::select('id')
            ->findOr($studentID, function () {
                throw new StudentNotExists();
            });

        $student->classrooms()->detach($classroomID);
    }

    public function findClassroom(int $studentID, int $classroomID)
    {

    }

    public function getAllClassroom(int $studentID)
    {

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

    public function getStudentByNISWithValidateClassroom(int $nis, int $classroomID)
    {

    }
}