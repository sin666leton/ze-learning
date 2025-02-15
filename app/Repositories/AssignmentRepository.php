<?php
namespace App\Repositories;

use App\Models\Assignment;
use App\Models\Subject;

class AssignmentRepository implements \App\Contracts\Assignment
{
    public function all()
    {
        return Assignment::all();
    }

    public function find(int $id)
    {
        return Assignment::findOrFail($id);
    }

    public function add(
        int $subjectID,
        string $title,
        string $content,
        string $access_at,
        string $ended_at,
        int $size
    )
    {
        return Subject::findOrFail($subjectID)
            ->assignments()->create([
                'title' => $title,
                'content' => $content,
                'access_at' => $access_at,
                'ended_at' => $ended_at,
                'size' => $size
            ]);
    }

    public function update(
        int $id,
        string $title,
        string $content,
        string $access_at,
        string $ended_at,
        int $size
    )
    {
        Assignment::findOrFail($id)->update([
            'title' => $title,
            'content' => $content,
            'access_at' => $access_at,
            'ended_at' => $ended_at,
            'size' => $size
        ]);
    }

    public function delete(int $id)
    {
        Assignment::findOrFail($id)->delete();
    }
}
