<?php

namespace App\Http\Controllers;

use App\Contracts\Question;
use App\Http\Requests\StoreAnswerAssignmentRequest;
use App\Http\Requests\StoreAnswerQuestionRequest;
use App\Http\Requests\StoreAttemptRequest;
use App\Services\AnswerAssignmentService;
use App\Services\AttemptService;
use App\Services\FileUploadService;
use App\Services\StudentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(
        protected StudentService $studentService
    ) {}

    public function dashboard()
    {
        return view('pages.student.dashboard');
    }

    public function subjectView(Request $request)
    {
        $classroom = $this->studentService->getLastClassroom($request->user()->student->id);
        return view('pages.student.subject.index', [
            'subjects' => $classroom->subjects()->get()
        ]);
    }

    public function subjectRead(Request $request, string $id)
    {
        $classroom = $this->studentService->getLastClassroom($request->user()->student->id);
        $subject = $classroom->subjects()->findOrFail($id);

        $assignments = $subject->assignments()->get()->map(function ($key) {
            $key->type = 'Tugas';
            $key->link = "/students/assignments/$key->id?subject=".$key->subject->id;
            return $key;
        });

        $quizzes = $subject->quizzes()->get()->map(function ($key) {
            $key->type = 'Kuis';
            $key->link = "/students/quizzes/$key->id?subject=".$key->subject->id;
            return $key;
        });

        $merge = $assignments->merge($quizzes)->sortByDesc('created_at');

        return view('pages.student.subject.read', [
            'subject' => $subject,
            'items' => $merge
        ]);
    }

    public function assignmentRead(Request $request, AnswerAssignmentService $answerAssignment)
    {
        $subjectID = $request->subject;

        if ($subjectID == null) return abort(404);

        $classroom = $this->studentService->getLastClassroom($request->user()->student->id);
        $subject = $classroom->subjects()->findOrFail($subjectID);

        $assignment = $subject->assignments()->findOrFail($request->id);

        $exists = $answerAssignment->studentAnswer($classroom->pivot->id);

        return view('pages.student.assignment.index', [
            'assignment' => $assignment,
            'exists' => $exists
        ]);
    }

    public function attemptQuiz(
        StoreAttemptRequest $request,
        AttemptService $attempt
    )
    {
        try {
            $attempt->create(
                $request->user()->student->id,
                $request->safe()->quiz_id
            );

            return response()->json([
                'message' => 'Berhasil'
            ], 200);
        } catch (ModelNotFoundException $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], $th->getCode());
        }
    }

    public function removeAttemptQuiz(
        string $id,
        AttemptService $attempt,
    )
    {
        try {
            $attempt->delete($id);
            return response()->json([
                'message' => 'Berhasil'
            ], 200);
        } catch (ModelNotFoundException $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], $th->getCode());
        }
    }

    public function answerAssignment(
        StoreAnswerAssignmentRequest $request,
        AnswerAssignmentService $answerAssignment,
        FileUploadService $fileUploadService
    )
    {
        // $id = $this->studentService->getLastClassroom($request->user()->student->id);
        $filename = $request->file('file')->getClientOriginalName();
        
        $encryptName = $fileUploadService->encryptFileName(
            $filename,
            'pdf'
        );
        $file = $fileUploadService->uploadAnswerAssignment(
            $request->file('file'),
            $encryptName
        );

        if ($fileUploadService->fileExists('/archive/assignment')) {
            $answerAssignment->create(
                [
                    'assignment_id' => $request->safe()->assignment_id,
                    'student_classroom_id' => $this->studentService->getLastClassroom($request->user()->student->id)->pivot->id,
                    'namespace' => $request->safe()->namespace == null ? $filename : $request->safe()->namespace.".pdf",
                    'link' => $fileUploadService->getFilePath($file)
                ]
            );

            return redirect()->back()->with('success', 'Kamu baru saja menyelesaikan tugas!');
        } else {
            return abort(500);
        }
    }

    public function answerQuestion(
        StoreAnswerQuestionRequest $request,
        Question $question
    )
    {
        $question->answer(
            $request->user()->student->id,
            $request->safe()->question_id,
            $request->safe()->content
        );

        return response()->json([
            'message' => 'Berhasil'
        ], 200);
    }
}
