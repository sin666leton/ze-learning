<?php

namespace App\Http\Controllers;

use App\Exceptions\QuizNotExists;
use App\Exceptions\StudentNotExists;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Score;
use App\Models\StudentClassroom;
use Illuminate\Http\Request;

class AnswerQuizController extends Controller
{
    public function show(Request $request, string $id)
    {
        $studentID = $request->student;
        if ($studentID == null) return abort(404);

        $pivot = StudentClassroom::with([
            'student' => function ($query) {
                $query->select(['id', 'user_id', 'nis'])
                    ->with([
                        'user' => function ($user) {
                            $user->select(['id', 'name']);
                        }
                    ]);
            }
        ])
        ->where('id', $studentID)
        ->firstOr(function () {
            throw new StudentNotExists();
        });

        $quiz = Quiz::with([
            'questions' => function ($questions) use (&$pivot) {
                $questions->select([
                    'id',
                    'quiz_id',
                    'type',
                    'content',
                    'point'
                ])
                ->withWhereHas('answerQuestion', function ($answer) use (&$pivot) {
                    $answer->select(['question_id', 'id', 'student_id', 'content', 'is_correct'])
                        ->where('student_id', $pivot->student->id);
                });
            }
        ])
        ->select(['id'])
        ->where('id', $id)
        ->firstOr(function () {
            throw new QuizNotExists();
        });

        $quiz->questions->where('type', 'mcq')->load([
            'choices' => function ($query) {
                $query->select(['id', 'question_id', 'content']);
            },
            'answerKey' => function ($query) {
                $query->select(['id', 'question_id', 'content']);
            }
        ]);

        $score = Score::select(['id', 'scoreable_type', 'scoreable_id', 'student_classroom_id', 'point', 'published'])
            ->where('student_classroom_id', $pivot->id)
            ->where('scoreable_id', $id)
            ->where('scoreable_type', 'App\Models\Quiz')
            ->first();

        $score->name = $pivot->student->user->name;

        return view('pages.admin.answer_quiz.index', [
            'score' => $score,
            'quiz' => $quiz
        ]);
    }
}
