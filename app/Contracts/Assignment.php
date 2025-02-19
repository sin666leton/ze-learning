<?php

namespace App\Contracts;

interface Assignment
{
    public function getFromSubject(int $subjectID): array;

    public function find(int $id): array;

    public function create(int $subjectID, string $title, string $content, int $size, string $access_at, string $ended_at): array;

    public function update(int $id, string $title, string $content, int $size, string $access_at, string $ended_at): array;

    public function delete(int $id): bool|null;
}
