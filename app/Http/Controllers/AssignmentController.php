<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Services\AssignmentService;
use App\Services\SubjectService;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function __construct(
        protected AssignmentService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, SubjectService $subject)
    {
        $subjectID = $request->subject;
        if ($subjectID == null) return abort(404);

        $sub = $subject->find($request->subject);
        $assignments = $sub->assignments()->get();

        return view('pages.admin.assignment.index', [
            'assignments' => $assignments,
            'subject' => $sub,
            'total' => count($assignments)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, SubjectService $subject)
    {
        $subjectID = $request->subject;
        if ($request->subject == null) return abort(404);

        $sub = $subject->find($subjectID);

        return view('pages.admin.assignment.create', [
            'subject' => $sub
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssignmentRequest $request)
    {
        $this->service->create($request->safe()->all());

        return redirect()->route('assignments.index', [
            'subject' => $request->safe()->subject_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $assignment = $this->service->find($id);
        $answers = $assignment->answerAssignments()->get();

        $totalStudent = $assignment->subject->classroom->students()->count();

        $attempted = count($answers);
        $notAttempted = $totalStudent - $attempted;

        return view('pages.admin.assignment.read', [
            'assignment' => $assignment,
            'answers' => $answers,
            'bad' => 0,
            'good'=> 0,
            'veryGood' => 0,
            'smart' => 0,
            'attempted' => $attempted,
            'notAttempted' => $notAttempted
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $assignment = $this->service->find($id);

        return view('pages.admin.assignment.edit', [
            'assignment' => $assignment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssignmentRequest $request, string $id)
    {
        $this->service->update($id, $request->safe()->all());
        $assignment = $this->service->find($id);
        
        return redirect()->route('assignments.index', [
            'subject' => $assignment->subject->id
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
