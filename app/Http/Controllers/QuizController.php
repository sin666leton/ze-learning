<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Services\QuizService;
use App\Services\SubjectService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, SubjectService $subject)
    {
        $subjectID = $request->subject;

        if ($subjectID == null) return abort(404);

        $sub = $subject->find($subjectID);
        $quizzes = $sub->quizzes()->get();
        return view('pages.admin.quiz.index', [
            'subject' => $sub,
            'quizzes' => $quizzes,
            'total' => count($quizzes)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, SubjectService $subject)
    {
        $subjectID = $request->subject;

        if ($subjectID == null) return abort(404);

        $sub = $subject->find($subjectID);

        return view('pages.admin.quiz.create', [
            'subject' => $sub
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuizRequest $request)
    {
        $this->service->create($request->safe()->all());

        return redirect()->route('quizzes.index', [
            'subject' => $request->safe()->subject_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $quiz = $this->service->find($id);
        $questions = $quiz->questions();
        $totalPoint = $questions->sum('point');
        $totalQuestion = $questions->count();

        $scores = $quiz->scores()->get();
        $totalStudent = $quiz->subject->classroom->students()->count();

        $attempted = $scores->count();
        $notAttempted = $totalStudent - $attempted;

        return view('pages.admin.quiz.read', [
            'quiz' => $quiz,
            'totalPoint' => $totalPoint,
            'totalQuestion' => $totalQuestion,
            'attempted' => $attempted,
            'notAttempted' => $notAttempted,
            'scores' => $scores,
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
        $quiz = $this->service->find($id);
        return view('pages.admin.quiz.edit', [
            'quiz' => $quiz
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuizRequest $request, string $id)
    {
        $this->service->update($id, $request->safe()->all());
        $quiz = $this->service->find($id);

        return redirect()->route('quizzes.index', [
            'subject' => $quiz->subject->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->service->delete($id);

        return redirect()->back();
    }
}
