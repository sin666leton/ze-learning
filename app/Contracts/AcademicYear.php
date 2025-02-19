<?php

namespace App\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AcademicYear
{
    public function paginate(int $each = 10): LengthAwarePaginator;

    /**
     * Cari tahun ajaran berdasarkan id
     * 
     * @param int $id
     * @return array{
     *  id: int,
     *  name: string
     * }
     */
    public function find(int $id): array;

    /**
     * Ambil semua tahun ajaran
     * 
     * @return array<int, array{
     *  id: int,
     *  name: string
     * }>
     */
    public function get(): array;

    /**
     * Ambil semua tahun ajaran
     * 
     * @return Collection<int, \App\Models\AcademicYear>
     */
    public function getCollection(): Collection;

    /**
     * Tambah tahun ajaran
     * 
     * @param string $name
     * @return array{
     *  id: int,
     *  name: string
     * }
     */
    public function create(string $name): array;

    /**
     * Perbarui tahun ajaran
     * @param int $id
     * @param string $name
     * @return array{
     *  id: int,
     *  name: string
     * }
     */
    public function update(int $id, string $name): array;

    /**
     * Hapus tahun ajaran
     * 
     * @param int $id
     * @return bool|null
     */
    public function delete(int $id): bool|null;
}
