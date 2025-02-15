<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\QuizNotExists;
use App\Exceptions\SubjectNotExists;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Models\Quiz;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subjectID = $request->subject;

        if ($subjectID == null) return abort(404);

        $subject = Subject::with([
                'quizzes' => function ($query) {
                    $query->select(['id', 'subject_id', 'title', 'content', 'access_at', 'ended_at', 'created_at']);
                }
            ])
            ->select(['id', 'classroom_id', 'name'])
            ->where('id', $subjectID)
            ->firstOr(function () {
                    throw new SubjectNotExists();
                }
            );

        return view('pages.admin.quiz.index', [
            'subject' => $subject,
            'total' => $subject->quizzes->count()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $subjectID = $request->subject;

        if ($subjectID == null) return abort(404);

        $subject = Subject::select(['id', 'name'])
            ->where('id', $subjectID)
            ->firstOr(function () {
                throw new SubjectNotExists();
            });

        return view('pages.admin.quiz.create', [
            'subject' => $subject
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuizRequest $request)
    {
        $access_at  = isset($request->safe()->access_at) ? $request->safe()->access_at : now();

        Subject::select('id')
            ->where('id', $request->safe()->subject_id)
            ->firstOrFail()
            ->quizzes()->create([
                'title' => $request->safe()->title,
                'content' => $request->safe()->content,
                'duration' => $request->safe()->duration,
                'access_at' => $access_at,
                'ended_at' => $request->safe()->ended_at
            ]);
        
        return redirect()->route('quizzes.index', [
            'subject' => $request->safe()->subject_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $quiz = Quiz::with([
                'scores' => function ($query) {
                    $query->select(['id', 'scoreable_id', 'student_classroom_id', 'point', 'created_at', 'published']);
                    $query->with([
                        'studentClassroom' => function ($pivot) {
                            $pivot->select(['id', 'student_id']);
                            $pivot->with([
                                'student' => function ($student) {
                                    $student->select(['id', 'user_id', 'nis']);
                                    $student->with([
                                        'user' => function ($user) {
                                            $user->select(['id', 'name']);
                                        }
                                    ]);
                                }
                            ]);
                        }
                    ]);
                },
                'questions' => function ($query) {
                    $query->select(['quiz_id', 'point']);
                }
            ])
            ->select([
                'id',
                'subject_id',
                'title',
                'content',
                'duration',
                'access_at',
                'ended_at',
                'created_at'
            ])
            ->where('id', $id)
            ->firstOrFail();

        $quiz->total_point = $quiz->questions->sum('point');
        $quiz->total_question = $quiz->questions->count();
        $quiz->attempted = $quiz->scores->count();
        $quiz->not_attempted = ($quiz->subject->classroom->students()->count() - $quiz->attempted);

        unset($quiz->questions);

        return view('pages.admin.quiz.read', [
            'quiz' => $quiz,
            'bad' => 0,
            'good' => 0,
            'veryGood' => 0,
            'smart' => 0
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $quiz = Quiz::with([
                'subject' => function ($query) {
                    $query->select(['id', 'name']);
                }
            ])
            ->select(['id', 'subject_id', 'title', 'content', 'access_at', 'duration', 'ended_at', 'created_at'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new QuizNotExists();
            });

        return view('pages.admin.quiz.edit', [
            'quiz' => $quiz
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuizRequest $request, string $id)
    {
        $quiz = Quiz::with([
            'subject' => function ($query) {
                $query->select(['id']);
            }
        ])
        ->select()
        ->where('id', $id)
        ->firstOr(function () {
            throw new QuizNotExists();
        });
        
        $access_at = isset($request->safe()->access_at) ? $request->safe()->access_at : now();

        $quiz->update([
            'title' => $request->safe()->title,
            'content' => $request->safe()->content,
            'duration' => $request->safe()->duration,
            'access_at' => $access_at,
            'ended_at' => $request->safe()->ended_at
        ]);

        return redirect()->route('quizzes.index', [
            'subject' => $quiz->subject->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Quiz::where('id', $id)->delete();

        return back();
    }
}
