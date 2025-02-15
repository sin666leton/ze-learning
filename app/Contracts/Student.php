<?php

namespace App\Contracts;

interface Student
{
    public function all();

    public function paginate(int $item = 10);

    public function find(int $id);

    public function add(
        string $email,
        string $name,
        string $password,
        int $nis
    );

    public function update(
        int $id,
        string $email,
        string $name,
        int $nis
    );

    public function delete(int $id);

    public function getStudentByNISWithValidateClassroom(int $nis, int $classroomID);

    public function getAllClassroom(
        int $studentID
    );

    public function findClassroom(
        int $studentID,
        int $classroomID
    );

    public function attachClassroom(
        int $studentID,
        int $classroomID
    );

    public function detachClassroom(
        int $studentID,
        int $classroomID
    );

    public function getLastAttachClassroom(int $studentID);
}
