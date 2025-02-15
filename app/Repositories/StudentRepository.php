<?php
namespace App\Repositories;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Hash;

class StudentRepository implements \App\Contracts\Student
{
    public function all()
    {
        return Student::with(['user'])->get();
    }

    public function paginate(int $item = 10)
    {
        return Student::paginate($item);
    }

    public function find(int $id)
    {
        return Student::findOrFail($id);
    }

    public function add(
        string $email,
        string $name, 
        string $password, 
        int $nis
    )
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

    public function update(
        int $id,
        string $email,
        string $name,
        int $nis
    )
    {
        $student = Student::findOrFail($id);
        $student->user->update([
            'email' => $email,
            'string' => $name,
        ]);

        $student->update([
            'nis' => $nis
        ]);
    }

    public function delete(int $id)
    {
        $user = User::whereHas('student', function ($query) use ($id) {
            $query->where('id', $id);
        })->first();

        session()->flash('success', "Pelajar ".$user->student->nis." telah dihapus");
        $user->delete();
    }

    public function getStudentByNISWithValidateClassroom(int $nis, int $classroomID)
    {
        return Student::with('user')->whereDoesntHave('classrooms', function ($query) use ($classroomID) {
            $query->where('classroom_id', $classroomID);
        })->where('nis', $nis)->firstOrFail(['id', 'user_id', 'nis']);
    }

    public function findClassroom(int $studentID, int $classroomID)
    {
        $student = $this->find($studentID);
        return $student->classrooms()->wherePivot('classroom_id', $classroomID)->firstOrFail();
    }

    public function getAllClassroom(int $studentID)
    {
        $student = $this->find($studentID);
        return $student->classrooms()->get();
    }

    public function attachClassroom(int $studentID, int $classroomID)
    {
        $clasroom = Classroom::findOrFail($classroomID);
        $student = $this->find($studentID);
        $student->classrooms()->attach($clasroom->id);
    }

    public function detachClassroom(int $studentID, int $classroomID)
    {
        $student = $this->find($studentID);
        $student->classrooms()->detach($classroomID);
    }

    public function getLastAttachClassroom(int $studentID)
    {
        return $this->find($studentID)
            ->classrooms()
            ->latest()
            ->first();
    }
}
