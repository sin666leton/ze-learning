<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Services\QuestionService;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class QuestionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('jsonOnly', except: ['index'])
        ];
    }

    public function __construct(
        protected QuestionService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, QuizService $quizService)
    {
        $quizID = $request->quiz;

        if ($quizID == null) return abort(404);

        $quiz = $quizService->find($quizID);

        return view('pages.admin.question.index', [
            'quiz' => $quiz
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionRequest $request)
    {
        $question = $this->service->create(
            $request->safe()->only([
                'quiz_id',
                'content',
                'point',
                'type'
            ])
        );
        
        if ($question->type == 'mcq') {
            $choices = $this->service->buildChoices(
                $question->id,
                $request->safe()->choices
            );

            $this->service->createChoices($choices);
            
            $this->service->createAnswerKey(
                $question,
                $request->safe()->answer
            );
        }

        return response()->json([
            'data' => $question->id
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionRequest $request, string $id)
    {
        $this->service->update(
            $id,
            $request->safe()->only([
                'content',
                'point',
                'type'
            ])
        );

        if ($request->type == 'mcq') {
            $this->service->updateChoices(
                $id,
                $request->safe()->choices
            );

            $this->service->updateAnswerKey(
                $id,
                $request->safe()->answer
            );
        }

        return response()->json([
            'message' => 'OK'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Pertanyaan telah dihapus'
        ], 200);
    }
}
