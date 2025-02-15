<?php

namespace App\Contracts;

interface Quiz
{
    public function all();

    public function find(int $id);

    public function add(
        int $subjectID,
        string $title,
        string $content,
        int $duration,
        string $access_at,
        string $ended_at
    );

    public function update(
        int $id,
        string $title,
        string $content,
        int $duration,
        string $access_at,
        string $ended_at
    );

    public function delete(int $id);
}
