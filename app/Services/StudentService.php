<?php
namespace App\Services;

use App\Contracts\Student;

class StudentService
{
    public function __construct(
        protected Student $repository
    ) {}

    public function paginate(int $item = 10)
    {
        return $this->repository->paginate($item);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Summary of create
     * @param array{
     *  email: string,
     *  name: string,
     *  password: string,
     *  nis: int
     * } $validatedInput
     */
    public function create(array $validatedInput)
    {
        $this->repository->add(
            $validatedInput['email'],
            $validatedInput['name'],
            $validatedInput['password'],
            $validatedInput['nis']
        );
    }

    /**
     * Summary of update
     * @param int $id
     * @param array{
     *  email: string,
     *  name: string,
     *  nis: int
     * } $validatedInput
     * @return void
     */
    public function update(int $id, array $validatedInput)
    {
        $this->repository->update(
            $id,
            $validatedInput['email'],
            $validatedInput['name'],
            $validatedInput['nis']
        );
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
    }

    public function addClassroom(
        int $studentID,
        int $classroomID
    )
    {
        $this->repository->attachClassroom(
            $studentID,
            $classroomID
        );
    }

    public function removeClassroom(
        int $studentID,
        int $classroomID
    )
    {
        $this->repository->detachClassroom(
            $studentID,
            $classroomID
        );
    }

    public function getLastClassroom(
        int $studentID
    )
    {
        return $this->repository->getLastAttachClassroom($studentID);
    }

    /**
     * Summary of findStudentWithValidateClass
     * @param array{
     *  nis: string,
     *  classroomID: string
     * } $validatedInput
     */
    public function findStudentWithValidateClass(array $validatedInput)
    {
        return $this->repository->getStudentByNISWithValidateClassroom(
            $validatedInput['nis'],
            $validatedInput['classroomID']
        );
    }
}