<?php

namespace App\Contracts;

interface Subject
{
    public function all();

    public function find(int $id);

    public function findOrFail(int $id);

    public function createFromClassroom(
        \App\Models\Classroom $classroom,
        int $semesterID,
        string $name,
        int|null $kkm
    );

    public function add(
        int $semesterID,
        int $classroomID,
        string $name,
        int $kkm
    );
    
    public function update(
        int $id,
        int $semesterID,
        string $name,
        int|null $kkm
    );

    public function delete(int $id);
}
