<?php
namespace App\Repositories;

use App\Models\Quiz;
use App\Models\Subject;

class QuizRepository implements \App\Contracts\Quiz
{
    public function all()
    {
        return Quiz::all();
    }

    public function find(int $id)
    {
        return Quiz::findOrFail($id);
    }

    public function add(
        int $subjectID,
        string $title,
        string $content,
        int $duration,
        string $access_at,
        string $ended_at
    )
    {
        Subject::findOrFail($subjectID)
            ->quizzes()->create([
                'title' => $title,
                'content' => $content,
                'duration' => $duration,
                'access_at' => $access_at,
                'ended_at' => $ended_at
            ]);
    }

    public function update(
        int $id,
        string $title,
        string $content,
        int $duration,
        string $access_at,
        string $ended_at
    )
    {
        Quiz::findOrFail($id)->update([
            'title' => $title,
            'content' => $content,
            'duration' => $duration,
            'access_at' => $access_at,
            'ended_at' => $ended_at
        ]);
    }

    public function delete(int $id)
    {
        Quiz::findOrFail($id)->delete();
    }
}
