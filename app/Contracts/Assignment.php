<?php

namespace App\Contracts;

interface Assignment
{
    public function all();

    public function find(int $id);
    
    public function add(
        int $subjectID,
        string $title,
        string $content,
        string $access_at,
        string $ended_at,
        int $size
    );

    public function update(
        int $id,
        string $title,
        string $content,
        string $access_at,
        string $ended_at,
        int $size
    );

    public function delete(int $id);
}
