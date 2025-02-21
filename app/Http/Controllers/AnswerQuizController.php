<?php

namespace App\Http\Controllers;

use App\Exceptions\QuizNotExists;
use App\Exceptions\ScoreNotExists;
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

        $score = Score::with([
            'studentClassroom' => function ($pivot) use (&$studentID) {
                $pivot->withWhereHas('student', function ($student) use (&$studentID) {
                    $student->with([
                        'user' => function ($user) {
                            $user->select(['id', 'name']);
                        }
                    ])
                    ->select(['id', 'user_id', 'nis'])
                    ->where('id', $studentID);
                })
                ->select('id', 'student_id');
            },
            'scoreable' => function ($morph) use (&$studentID) {
                $morph->with([
                    'questions' => function ($questions) use (&$studentID) {
                        $questions->select([
                            'id',
                            'quiz_id',
                            'type',
                            'content',
                            'point'
                        ])
                        ->with([
                            'answerQuestion' => function ($answer) use (&$studentID) {
                                $answer->select(['question_id', 'id', 'student_id', 'content', 'is_correct'])
                                    ->where('student_id', $studentID);
                            },
                            'choices' => function ($query) {
                                $query->select(['id', 'question_id', 'content'])
                                    ->whereHas('question', function ($q) {
                                        $q->where('type', 'mcq');
                                    });
                            },
                            'answerKey' => function ($query) {
                                $query->select(['id', 'question_id', 'content'])
                                    ->whereHas('question', function ($q) {
                                        $q->where('type', 'mcq');
                                    });
                            }
                        ]);
                    }
                ])
                ->select('id');
            }
        ])
        ->select(['id', 'student_classroom_id', 'scoreable_id', 'scoreable_type', 'point', 'published', 'created_at'])
        ->where('id', $id)
        ->firstOr(function () {
            throw new ScoreNotExists();
        });

        if (!$score->studentClassroom) throw new StudentNotExists();

        $score->created = $score->created_at->format('d-m-Y H:i');

        return view('pages.admin.answer_quiz.index', [
            'score' => $score->toArray(),
        ]);
    }
}
