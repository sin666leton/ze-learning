<?php

namespace App\Contracts;

interface Semester
{
    public function all();

    public function paginate(
        int $item = 10
    );

    public function getByID(int $id);

    /**
     * Summary of add
     * @param int $academicYearID
     * @param array{name: string, start: string, end: string} $data
     */
    public function add(int $academicYearID, array $data);

    /**
     * Summary of update
     * @param int $id
     * @param array{name: string, start: string, end: string} $data
     */
    public function update(int $id, array $data);

    public function delete(int $id);

    public function whereHasAcademicYear(int $academicYear);
}
