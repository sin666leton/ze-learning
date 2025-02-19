<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface Classroom
{
    public function find(int $id, string $relation = 'subject', int|null $semesterID = null);

    /**
     * Ambil semua kelas dengan tahun ajaran
     * 
     * @param \Illuminate\Database\Eloquent\Collection<int, \App\Models\AcademicYear> $acacademicYear
     * @return array
     */
    public function loadFromCollection(Collection $acacademicYear): array;

    public function create(int $academicYearID, string $name): array;

    public function update(int $id, string $name): array;

    public function delete(int $id): bool|null;
}
