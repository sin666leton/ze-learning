<?php
namespace App\Repositories\V1;

use App\Exceptions\AcademicYearNotExists;
use App\Models\AcademicYear;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ToArray;

class AcademicYearRepository implements \App\Contracts\AcademicYear
{
    public function paginate(int $each = 10): LengthAwarePaginator
    {
        return AcademicYear::select(['id', 'name'])
            ->withCount(['semesters', 'classrooms'])
            ->paginate($each);
    }

    public function get(): array
    {
        return AcademicYear::select(['id', 'name'])
            ->get()
            ->toArray();
    }

    public function getCollection(): Collection
    {
        return AcademicYear::select(['id', 'name'])
            ->get();
    }

    public function find(int $id): array
    {
        $academicYear = AcademicYear::select(['id', 'name'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new AcademicYearNotExists();
            });

        return $academicYear->toArray();
    }

    public function create(string $name): array
    {
        $academicYear = AcademicYear::create([
            'name' => $name
        ]);

        return $academicYear->only(['id', 'name']);
    }

    public function update(int $id, string $name): array
    {
        $academicYear = AcademicYear::select(['id', 'name'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new AcademicYearNotExists();
            });

        $academicYear->update([
            'name' => $name
        ]);

        return $academicYear->only(['id', 'name']);
    }

    public function delete(int $id): bool|null
    {
        $academicYear = AcademicYear::select('id')
            ->where('id', $id)
            ->firstOr(function () {
                throw new AcademicYearNotExists();
            });

        return $academicYear->delete();
    }
}