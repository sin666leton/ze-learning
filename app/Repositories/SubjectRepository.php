<?php
namespace App\Repositories;

use App\Models\Classroom;
use App\Models\Subject;

class SubjectRepository implements \App\Contracts\Subject
{
    public function all()
    {
        return Subject::all();
    }

    public function find(int $id)
    {
        return Subject::find($id);
    }

    public function findOrFail(int $id)
    {
        return Subject::findOrFail($id);
    }

    public function createFromClassroom(
        Classroom $classroom,
        int $semesterID,
        string $name, 
        int|null $kkm
    )
    {
        return $classroom->subjects()->create([
            'semester_id' => $semesterID,
            'name' => $name,
            'kkm' => $kkm == null ? 70 : $kkm
        ]);
    }

    public function add(
        int $semesterID,
        int $classroomID,
        string $name,
        int $kkm
    )
    {
        Subject::create([
            'classroom_id' => $classroomID,
            'semester_id' => $semesterID,
            'name' => $name,
            'kkm' => $kkm
        ]);
    }

    public function update(
        int $id,
        int $semesterID,
        string $name,
        int|null $kkm
    )
    {
        Subject::findOrFail($id)->update([
                'semester_id' => $semesterID,
                'name' => $name,
                'kkm' => $kkm == null ? 70 : $kkm
            ]);
    }

    public function delete(int $id)
    {
        Subject::findOrFail($id)->delete();
    }
}
