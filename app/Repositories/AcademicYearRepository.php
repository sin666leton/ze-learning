<?php
namespace App\Repositories;

use App\Models\AcademicYear;

class AcademicYearRepository implements \App\Contracts\AcademicYear
{
    private array $column = [
        'id',
        'name'
    ];

    public function all()
    {
        return AcademicYear::all($this->column);
    }

    public function paginate(int $item = 10)
    {
        return AcademicYear::paginate($item);
    }

    public function find(int $id)
    {
        return AcademicYear::findOrFail($id, $this->column);
    }

    public function add(string $name)
    {
        return AcademicYear::create([
            "name" => $name
        ]);
    }

    public function update(int $id, string $name)
    {
        return AcademicYear::findOrFail($id)->update([
            "name" => $name
        ]);
    }

    public function delete(int $id)
    {
        AcademicYear::findOrFail($id)->delete();
    }

    public function latest()
    {
        return AcademicYear::latest()->first();
    }
}