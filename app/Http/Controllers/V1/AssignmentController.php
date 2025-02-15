<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\AssignmentNotExists;
use App\Exceptions\SubjectNotExists;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Models\Subject;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subjectID = $request->subject;
        if ($subjectID == null) return abort(404);

        $subject = Subject::with([
            'assignments' => function ($query) {
                $query->select([
                    'id',
                    'subject_id',
                    'title',
                    'content',
                    'size',
                    'created_at',
                    'access_at',
                    'ended_at'
                ]);
            }
        ])
        ->select()
        ->where('id', $subjectID)
        ->firstOr(function () {
            throw new SubjectNotExists();
        });

        return view('pages.admin.assignment.index', [
            'subject' => $subject,
            'total' => $subject->assignments->count()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $subjectID = $request->subject;
        if ($request->subject == null) return abort(404);

        $sub = Subject::select(['id', 'name'])
            ->where('id', $subjectID)
            ->firstOr(function () {
                throw new SubjectNotExists();
            });

        return view('pages.admin.assignment.create', [
            'subject' => $sub
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssignmentRequest $request)
    {
        $access_at = isset($request->safe()->access_at) ? $request->safe()->access_at : now();

        Subject::select('id')
            ->findOrFail($request->safe()->subject_id)
            ->assignments()
            ->create([
                'title' => $request->safe()->title,
                'content' => $request->safe()->content,
                'access_at' => $access_at,
                'ended_at' => $request->safe()->ended_at,
                'size' => $request->safe()->size
            ]);

        return redirect()->route('assignments.index', [
            'subject' => $request->safe()->subject_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $assignment = Assignment::with([
            'answerAssignments' => function ($query) {
                $query->select([
                    'id',
                    'assignment_id',
                    'student_classroom_id',
                    'link',
                    'namespace',
                    'created_at'
                ])
                ->with([
                    'score' => function ($scores) {
                        $scores->select([
                            'id',
                            'scoreable_id',
                            'scoreable_type',
                            'student_classroom_id',
                            'point',
                            'published'
                        ]);
                    }
                ])
                ->with([
                    'studentClassroom' => function ($pivot) {
                        $pivot->select(['id', 'student_id'])
                            ->with([
                                'student' => function ($student) {
                                    $student->select(['id', 'user_id', 'nis'])
                                        ->with([
                                            'user' => function ($user) {
                                                $user->select(['id', 'name']);
                                            }
                                        ]);
                                }
                            ]);
                    }
                ]);
            }
        ])
        ->select([
            'id',
            'subject_id',
            'title',
            'content',
            'created_at',
            'access_at',
            'ended_at',
            'size'
        ])
        ->where('id', $id)
        ->firstOr(function () {
            throw new AssignmentNotExists();
        });

        $assignment->attempted = $assignment->answerAssignments->count();
        $assignment->not_attempted = ($assignment->subject->classroom->students()->count() - $assignment->attempted);

        return view('pages.admin.assignment.read', [
            'assignment' => $assignment,
            'bad' => 0,
            'good'=> 0,
            'veryGood' => 0,
            'smart' => 0,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $assignment = Assignment::select()
            ->where('id', $id)
            ->firstOr(function () {
                throw new AssignmentNotExists();
            });

        return view('pages.admin.assignment.edit', [
            'assignment' => $assignment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssignmentRequest $request, string $id)
    {
        $assignment = Assignment::with([
            'subject' => function ($query) {
                $query->select('id');
            }
        ])
        ->select(['subject_id', 'id', 'title', 'content', 'access_at', 'ended_at', 'size'])
        ->where('id', $id)
        ->firstOr(function () {
            throw new AssignmentNotExists();
        });

        $access_at = isset($request->safe()->access_at) ? $request->safe()->access_at : now();

        $assignment->update([
            'title' => $request->safe()->title,
            'content' => $request->safe()->content,
            'access_at' => $access_at,
            'ended_at' => $request->safe()->ended_at,
            'size' => $request->safe()->size
        ]);

        return redirect()->route('assignments.index', [
            'subject' => $assignment->subject->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Assignment::select('id')
            ->where('id', $id)
            ->delete();

        return redirect()->back();
    }
}
