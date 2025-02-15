<?php

namespace App\Contracts;

interface AcademicYear
{
    public function all();

    public function paginate(int $item = 10);

    public function find(int $id);

    public function add(string $name);

    public function update(int $id, string $name);

    public function delete(int $id);

    public function latest();
}
