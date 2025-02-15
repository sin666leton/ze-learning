<?php
namespace App\Contracts\Controllers;

use App\Http\Requests\StoreAnswerAssignmentRequest;
use App\Http\Requests\StoreAnswerQuestionRequest;
use App\Http\Requests\StoreAttemptRequest;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

interface Student
{
    public function dashboard();

    public function subjectView(Request $request);

    public function subjectRead(Request $request, string $id);

    public function assignmentRead(Request $request, string $id);

    public function attemptQuiz(StoreAttemptRequest $request);

    public function removeAttemptQuiz(Request $request, string $id);

    public function answerAssignment(StoreAnswerAssignmentRequest $request, FileUploadService $fileUploadService);

    public function answerQuestion(StoreAnswerQuestionRequest $request);

}