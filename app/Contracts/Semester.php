<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface Semester
{
    /**
     * Cari semester berdasarkan id
     * 
     * @param int $id
     * @return array{
     *  id: int,
     *  academic_year_id: int,
     *  name: string,
     *  academic_year: array{
     *      id: int,
     *      name: string
     *  }
     * }
     */
    public function find(int $id): array;

    public function getFromAcademicYear(int $academicYearID): array;

    /**
     * Ambil semester dari collection tahun ajaran
     * 
     * @param \Illuminate\Database\Eloquent\Collection<int, \App\Models\AcademicYear> $academicYear
     * @return array<int, array{
     *  academic_year_id: int,
     *  id: int,
     *  name: string
     * }>
     */
    public function loadFromCollection(Collection $academicYear): array;

    /**
     * Tambah semester baru
     * 
     * @param int $academicYearID
     * @param string $name
     * @return array{
     *  id: int,
     *  academic_year_id: int,
     *  name: string
     * }
     */
    public function create(int $academicYearID, string $name): array;

    /**
     * Perbarui semester
     * 
     * @param int $id
     * @param string $name
     * @return array{
     *  id: int,
     *  academic_year_id: int,
     *  name: string
     * }
     */
    public function update(int $id, string $name): array;

    /**
     * Hapus semester
     * 
     * @param int $id
     * @return bool|null
     */
    public function delete(int $id): bool|null;
}
