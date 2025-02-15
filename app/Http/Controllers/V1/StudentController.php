<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\AssignmentNotExists;
use App\Exceptions\AttemptNotExists;
use App\Exceptions\QuestionNotExists;
use App\Exceptions\QuizNotExists;
use App\Exceptions\StudentNotExists;
use App\Exceptions\SubjectNotExists;
use App\Exceptions\UploadFileFailed;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AnswerAssignment;
use App\Models\Assignment;
use App\Models\Attempt;
use App\Models\Classroom;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\Subject;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StudentController extends Controller implements \App\Contracts\Controllers\Student
{
    public function __construct(
        protected \App\Contracts\Student $student
    ) {}

    private function refreshLastClassroom(int $studentID)
    {
        // Cache::remember($studentID."_student", 30, function () {
        //     $this->student->getLastAttachClassroom()
        // });
    }

    public function dashboard()
    {
        return view('pages.student.dashboard');
    }

    public function subjectView(Request $request)
    {
        $user = $request->user();
        $query = Cache::remember($user->id."_subjects", 30, function () use ($user) {
            $query = Student::with(['classrooms' => function ($classroom) {
                $classroom->select('classrooms.id', 'classrooms.name')
                ->latest('student_classroom.id')
                ->limit(1);
            }])
            ->select(['id'])
            ->findOr($user->student->id, function () {
                throw new StudentNotExists();
            });

            return $query->classrooms
                ->first()
                ->subjects()
                ->with(['semester' => function ($query) {
                    $query->select(['id', 'name']);
                }])
                ->get(['semester_id', 'id', 'name', 'kkm']);
        });


        return view('pages.student.subject.index', [
            'subjects' => $query->toArray()
        ]);
    }

    public function subjectRead(Request $request, string $id)
    {
        $user = $request->user();

        $query = Cache::remember($user->id."_assignments_quizzes", 30, function () use ($user, $id) {
            $latestClassroom = Student::with(['classrooms' => function ($classroom) {
                $classroom->select('classrooms.id', 'classrooms.name')
                ->latest('student_classroom.id')
                ->limit(1);
            }])
            ->select(['id'])
            ->findOr($user->student->id, function () {
                throw new StudentNotExists();
            })
            ->classrooms
            ->first();

            $now = now();

            $subject = $latestClassroom
                ->subjects()
                ->with([
                    'assignments' => function ($query) use ($now) {
                        $query->select([
                            'id',
                            'subject_id',
                            'title',
                            'content',
                            'size',
                            'access_at',
                            'ended_at',
                            'created_at'
                        ])
                        ->where('ended_at', '>', $now)
                        ->where('access_at', '<=', $now);
                        
                    },
                    'quizzes' => function ($query) use ($now) {
                        $query->select([
                            'id',
                            'subject_id',
                            'title',
                            'content',
                            'duration',
                            'access_at',
                            'ended_at',
                            'created_at'
                        ])
                        ->where('ended_at', '>', $now)
                        ->where('access_at', '<=', $now);
                    }
                ])
                ->select(['id', 'classroom_id', 'name', 'kkm'])
                ->findOr($id, function () {
                    throw new SubjectNotExists();
                });

            $subject->quizzes->map(function ($key) {
                $key->type = 'Kuis';
                $key->link = '/students/quizzes/'.$key->id.'?subject='.$key->subject_id;
                $key->created = $key->created_at->format('d-m-Y H:i');

                unset($key->created_at);
                return $key;
            });

            $subject->assignments->map(function ($key) {
                $key->type = 'Tugas';
                $key->link = '/students/assignments/'.$key->id.'?subject='.$key->subject_id;
                $key->created = $key->created_at->format('d-m-Y H:i');

                unset($key->created_at);
                return $key;
            });

            $items = $subject->quizzes->concat($subject->assignments)->sortByDesc('created_at')->values();

            $subject->items = $items->toArray();
            unset($subject->assignments);
            unset($subject->quizzes);

            return $subject;
        });

        return view('pages.student.subject.read', $query->toArray());
    }

    public function assignmentRead(Request $request, string $id)
    {
        $user = $request->user();

        $latestClassroom = Student::with(['classrooms' => function ($classroom) {
                $classroom->select('classrooms.id', 'classrooms.name')
                ->latest('student_classroom.id')
                ->limit(1);
            }])
            ->select(['id'])
            ->findOr($user->student->id, function () {
                throw new StudentNotExists();
            })
            ->classrooms
            ->first();

        $subject = $latestClassroom
            ->subjects()
            ->with(['assignments' => function ($query) use ($id) {
                $query->select([
                    'subject_id',
                    'id',
                    'title',
                    'content',
                    'size',
                    'access_at',
                    'ended_at',
                    'created_at'
                ])
                ->where('id', $id);
            }])
            ->select(['id', 'classroom_id', 'semester_id', 'name'])
            ->findOr($request->subject, function () {
                throw new SubjectNotExists();
            });

        $subject->assignments->map(function ($query) {
            $query->stat = $query->status;
            return $query;
        });

        $subject = $subject->toArray();
        $assignment = $subject['assignments'][0];
        $answer = AnswerAssignment::select(['namespace', 'link'])
            ->where('assignment_id', $assignment['id'])
            ->where('student_classroom_id', $latestClassroom->pivot->id)
            ->first();

        return view('pages.student.assignment.index', [
            'assignment' => $assignment,
            'exists' => $answer == null ? false : $answer->toArray()
        ]);

    }

    public function quizRead(Request $request, string $id)
    {
        $user = $request->user();

        $latestClassroom = Student::with(['classrooms' => function ($classroom) {
                $classroom->select('classrooms.id', 'classrooms.name')
                ->latest('student_classroom.id')
                ->limit(1);
            }])
            ->select(['id'])
            ->findOr($user->student->id, function () {
                throw new StudentNotExists();
            })
            ->classrooms
            ->first();

        $subject = $latestClassroom
            ->subjects()
            ->with(['quizzes' => function ($query) use ($id) {
                $query->select([
                    'subject_id',
                    'id',
                    'title',
                    'content',
                    'duration',
                    'access_at',
                    'ended_at',
                    'created_at'
                ])
                ->where('id', $id);
            }])
            ->select(['id', 'classroom_id', 'semester_id', 'name'])
            ->findOr($request->subject, function () {
                throw new SubjectNotExists();
            });

        $subject->quizzes->map(function ($query) use ($latestClassroom) {
            $query->stat = $query->status;
            $query->score = $query->scores()
                ->select(['id', 'student_classroom_id', 'point', 'published'])
                ->where('student_classroom_id', $latestClassroom->pivot->id)
                ->first();

            return $query;
        });

        $subject = $subject->toArray();
        $quiz = $subject['quizzes'][0];


        return view('pages.student.quiz.index', [
            'quiz' => $quiz
        ]);
    }

    public function questionView(Request $request)
    {
        $user = $request->user();
        $studentID = $user->student->id;
        $attempt = $user->student->attempt;

        $questions = Question::with([
                'choices' => function ($query) {
                    $query->select(['id', 'question_id', 'content']);
                },
                'answerKey' => function ($query) {
                    $query->select(['id', 'question_id', 'content']);
                },
                'answerQuestion' => function ($query) use ($studentID) {
                    $query->select(['id', 'question_id', 'student_id', 'content'])
                        ->where('student_id', $studentID);
                }
            ])
            ->select(['quiz_id', 'id', 'content', 'type', 'point'])
            ->where('quiz_id', $attempt->quiz_id)
            ->get()
            ->map(function ($key) {
                if ($key->type == 'mcq') {
                    $merge = array_merge(array_values([
                        ...$key->choices->toArray(),
                        $key->answerKey->toArray()
                    ]));

                    shuffle($merge);

                    $key->options = $merge;
                }

                if ($key->answerQuestion) {
                    $key->answer = $key->answerQuestion->toArray();
                }

                unset($key->choices);
                unset($key->answerKey);
                unset($key->answerQuestion);

                return $key;
            });

        $time_left = now()->diffInSeconds($attempt->time);

        return view('pages.student.question.index', [
           'questions' => $questions->toArray(),
           'time_left' => abs($time_left),
           'attempt_id' => $attempt['id']
        ]);
    }

    public function attemptQuiz(\App\Http\Requests\StoreAttemptRequest $request)
    {
        $user = $request->user();

        $latestClassroom = Student::with(['classrooms' => function ($classroom) {
                $classroom->select('classrooms.id', 'classrooms.name')
                ->latest('student_classroom.id')
                ->limit(1);
            }])
            ->select(['id'])
            ->findOr($user->student->id, function () {
                throw new StudentNotExists();
            })
            ->classrooms
            ->first();

        $quizID = $request->safe()->quiz_id;

        $subject = $latestClassroom->subjects()
            ->with([
                'quizzes' => function ($query) use ($quizID) {
                    $query->select(['id', 'subject_id', 'duration'])
                        ->where('id', $quizID);
                }
            ])
            ->select(['id', 'classroom_id'])
            ->where('id', $request->safe()->subject_id)
            ->firstOr(function () {
                throw new SubjectNotExists();
            });

        $quiz = $subject->quizzes[0];

        $quiz->attempts()
            ->create([
                'student_id' => $user->student->id,
                'time' => now()->addMinutes($quiz->duration)->format('Y-m-d H:i:s')
            ]);

        return response()->json([
            'message' => 'Berhasil'
        ], 200);
    }

    public function removeAttemptQuiz(Request $request, string $id)
    {
        $student = $request->user()->student;
        $studentID = $student->id;

        $latestClassroom = Student::with(['classrooms' => function ($classroom) {
            $classroom->select('classrooms.id', 'classrooms.name')
            ->latest('student_classroom.id')
            ->limit(1);
        }])
        ->select(['id'])
        ->findOr($studentID, function () {
            throw new StudentNotExists();
        })
        ->classrooms
        ->first();

        $totalStudentPoint = 0;
        $totalQuestionPoint = 0;

        $attempt = Attempt::with([
                'quiz' => function ($query) {
                    $query->select(['id']);
                }
            ])
            ->select(['student_id', 'quiz_id', 'id'])
            ->where('student_id', $studentID)
            ->where('id', $id)
            ->firstOr(function () {
                throw new AttemptNotExists();
            });

        Question::with([
                'answerQuestion' => function ($query) use ($studentID) {
                    $query->select(['question_id', 'student_id', 'id', 'is_correct'])
                        ->where('student_id', $studentID);
                }
            ])
            ->select(['id', 'quiz_id', 'point'])
            ->where('quiz_id', $attempt->quiz_id)
            ->chunk(100, function ($questions) use (&$totalStudentPoint, &$totalQuestionPoint) {
                $totalQuestionPoint = $questions->sum('point');
                foreach ($questions as $question) {
                    if (!empty($question->answerQuestion()->exists())) {
                        if ($question->answerQuestion[0]->is_correct) $totalStudentPoint += $question->point;
                    }
                }
            });

        $attempt->quiz->scores()->updateOrCreate([
            'student_classroom_id' => $latestClassroom->pivot->id,
        ], [
            'point' => ($totalStudentPoint / $totalQuestionPoint) * 100,
            'published' => false
        ]);

        $attempt->delete();

        return response()->json([
            'message' => 'Berhasil'
        ], 200);
    }

    public function answerAssignment(\App\Http\Requests\StoreAnswerAssignmentRequest $request, FileUploadService $fileUploadService)
    {
        $user = $request->user();
        $latestClassroom = Student::with(['classrooms' => function ($classroom) {
                $classroom->select('classrooms.id', 'classrooms.name')
                    ->latest('student_classroom.id')
                    ->limit(1);
            }])
            ->select(['id'])
            ->findOr($user->student->id, function () {
                throw new StudentNotExists();
            })
            ->classrooms
            ->first();

        $filename = $request->file('file')->getClientOriginalName();

        $encryptName = $fileUploadService->encryptFileName(
            $filename,
            'pdf'
        );
        $file = $fileUploadService->uploadAnswerAssignment(
            $request->file('file'),
            $encryptName
        );

        if (!$fileUploadService->fileExists('/archive/assignment')) throw new UploadFileFailed();

        $answer = Assignment::findOr(
            $request->safe()->assignment_id,
            function () {
                throw new AssignmentNotExists();
            }
        )
        ->answerAssignments()->create([
            'student_classroom_id' => $latestClassroom->pivot->id,
            'namespace' => $request->safe()->namespace == null ? $filename : $request->safe()->namespace.".pdf",
            'link' => $fileUploadService->getFilePath($file)
        ]);

        $answer->score()->create([
            'student_classroom_id' => $latestClassroom->pivot->id,
            'point' => 100
        ]);

        return redirect()->back()->with('success', 'Kamu baru saja menyelesaikan tugas!');

    }

    public function answerQuestion(\App\Http\Requests\StoreAnswerQuestionRequest $request)
    {
        $isCorrect = false;
        $student = $request->user()->student;
        $questionID = $request->safe()->question_id;
        $content = $request->safe()->content;
        $studentID = $student->id;

        $question = Question::with([
            'answerKey' => function ($query) {
                $query->select(['question_id', 'id', 'content']);
            }
        ])
        ->select(['quiz_id', 'id', 'content', 'type'])
        ->where('quiz_id', $student->attempt->quiz_id)
        ->where('id', $questionID)
        ->firstOr(function () {
            throw new QuestionNotExists();
        });
        
        if ($question->type == 'mcq') {
            $isCorrect = $question->answerKey->content == $content;
        }

        $question->answerQuestion()->updateOrCreate([
            'student_id' => $studentID,
            'question_id' => $questionID
        ], [
            'student_id' => $studentID,
            'question_id' => $questionID,
            'content' => $content,
            'is_correct' => $isCorrect
        ]);

        return response()->json([
            'message' => 'Berhasil'
        ], 200);
    }

    public function scoreView(Request $request)
    {
        $student = $request->user()->student;

        $scores = [];
        $pivot = StudentClassroom::with([
                'classroom' => function ($query) {
                    $query->select(['id', 'name']);
                }
            ])
            ->select(['id', 'student_id', 'classroom_id'])
            ->where('student_id', $student->id)->get();

        if ($request->has('classroom')) {
            $scores = Score::with('scoreable')
            ->select([
                'id',
                'scoreable_id',
                'scoreable_type',
                'created_at',
                'published',
                'point'
            ])
            ->where('student_classroom_id', $request->classroom)
            ->get()
            ->map(function ($morph) {
                if ($morph->scoreable_type == 'App\Models\Quiz') {
                    $morph->type = 'Kuis';
                    $morph->title = $morph->scoreable->title;
                    $morph->subject = $morph->scoreable->subject->name;
                } else {
                    $morph->type = 'Tugas';
                    $morph->title = $morph->scoreable->assignment->title;
                    $morph->subject = $morph->scoreable->assignment->subject->name;
                }

                unset($morph->scoreable);
                return $morph;
            })
            ->toArray();
        }
        // $arr = $pivot->toArray();
        // dd($scores);

        return view('pages.student.score.index', [
            'student' => $pivot->toArray(),
            'scores' => $scores
        ]);
    }
}
