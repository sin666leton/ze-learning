<?php

namespace App\Contracts;

interface Classroom
{
    public function all();

    public function find(int $id);

    public function withAcademicYearAndSemesterFind(int $id): \App\Models\Classroom;

    /**
     * Summary of add
     * @param int $academicYearID
     * @param array{name: string} $data
     */
    public function add(int $academicYearID, array $data);
    
    /**
     * Summary of update
     * @param int $id
     * @param array{name: string} $data
     * @return void
     */
    public function update(int $id, array $data);

    public function delete(int $id);

    public function whereHasAcademicYear(int $id);
}
