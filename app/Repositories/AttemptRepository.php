<?php
namespace App\Repositories;

use App\Models\AnswerQuestion;
use App\Models\Attempt;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttemptRepository implements \App\Contracts\Attempt
{
    public function get(int $studentID)
    {
        return Attempt::where('student_id', $studentID)->first();
    }

    public function create(int $studentID, int $quizID)
    {
        Quiz::findOr($quizID, function () {
            throw new ModelNotFoundException("Kuis tidak ditemukan", 404);
        })->attempts()
            ->create([
                'student_id' => $studentID,
                'time' => now()->format('Y-m-d H:i:s')
            ]);
    }

    public function delete(int $id)
    {
        $attempt = Attempt::with('quiz')
            ->findOr($id, function (){
                throw new ModelNotFoundException("Attempt tidak ditemukan", 404);
            });

        $id = $attempt->student->classrooms()
            ->latest()
            ->first()
            ->id;

        $this->createScoreFromAttempt(
            $attempt,
            $id
        );

        return $attempt->delete();
    }

    public function exists(int $studentID)
    {
        return Attempt::where('student_id', $studentID)
            ->exists();
    }

    public function createScoreFromAttempt(
        Attempt $attempt,
        int $studentClassroomID
    )
    {
        $score = $this->countScore(
            $attempt->student->id,
            $attempt->quiz_id
        );

        $attempt->quiz->scores()->create([
            'student_classroom_id' => $studentClassroomID,
            'point' => $score
        ]);
    }

    private function countScore(
        int $studentID,
        int $quizID
    )
    {
        $totalPoint = Question::where('quiz_id', $quizID)
            ->sum('point');

        $point = AnswerQuestion::withWhereHas(
            'question',
            function ($query) use ($quizID) {
                $query->where('quiz_id', $quizID);
            })
            ->where('student_id', $studentID)
            ->where('is_correct', true)
            ->get()
            ->sum('question.point');

        return ($point / $totalPoint) * 100;
    }
}
