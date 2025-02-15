<?php
namespace App\Services;

use App\Contracts\Classroom;
use App\Contracts\Subject;

class SubjectService
{
    public function __construct(
        protected Subject $subjectRepository,
        protected Classroom $classroomRepository
    ) {}


    /**
     * Summary of create
     * @param array{
     *  semester_id: int,
     *  classroom_id: int,
     *  name: string,
     *  kkm: int
     * } $validatedInput
     */
    public function create(array $validatedInput)
    {
        $classroom = $this->classroomRepository->withAcademicYearAndSemesterFind($validatedInput['classroom_id']);
        
        if (!$classroom->academicYear->semesters()->where('id', $validatedInput['semester_id'])->first()) {
            return abort(404);
        } else {
            return $this->subjectRepository->createFromClassroom(
                $classroom,
                $validatedInput['semester_id'],
                $validatedInput['name'],
                $validatedInput['kkm']
            );
        }
    }

    /**
     * Summary of update
     * @param array{
     *  semester_id: int,
     *  name: string,
     *  kkm: string|null  
     * } $validatedInput
     * @return void
     */
    public function update(int $id, array $validatedInput)
    {
        $this->subjectRepository->update(
            $id,
            $validatedInput['semester_id'],
            $validatedInput['name'],
            $validatedInput['kkm']
        );
    }

    public function delete(int $id)
    {
        $this->subjectRepository->delete($id);
    }

    public function find(int $id)
    {
        return $this->subjectRepository->findOrFail($id);
    }
}