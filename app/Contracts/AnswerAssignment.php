<?php

namespace App\Contracts;

interface AnswerAssignment
{
    public function find(int $id);
    
    public function add(
        int $student_classroom_id,
        int $assignment_id,
        string $namespace,
        string $link
    );

    public function delete(int $id); 

    public function createScore();

    public function findStudent(int $id);
}
