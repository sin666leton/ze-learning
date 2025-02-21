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
    public function __construct(
        protected \App\Contracts\Assignment $assignment
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subjectID = $request->subject;
        if ($subjectID == null) return abort(404);

        $subject = $this->assignment->getFromSubject($subjectID);

        return view('pages.admin.assignment.index', [
            'navLink' => [
                ['url' => '/admin/classrooms', 'label' => 'Kelas'],
                ['url' => '/admin/classrooms/'.$subject['classroom']['id'], 'label' => $subject['classroom']['name']],
                ['url' => '/admin/subjects/'.$subject['id'], 'label' => $subject['name']],
                ['url' => '#', 'label' => 'Penugasan'],
            ],
            'subject' => $subject
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, \App\Contracts\Subject $subject)
    {
        $subjectID = $request->subject;
        if ($request->subject == null) return abort(404);

        $data = $subject->find($subjectID);

        return view('pages.admin.assignment.create', [
            'navLink' => [
                ['url' => '/admin/classrooms', 'label' => 'Kelas'],
                ['url' => '/admin/classrooms/'.$data['classroom']['id'], 'label' => $data['classroom']['name']],
                ['url' => '/admin/subjects/'.$data['id'], 'label' => $data['name']],
                ['url' => '/admin/assignments?subject='.$data['id'], 'label' => 'Penugasan'],
                ['url' => '#', 'label' => 'Tambah'],
            ],
            'subject' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssignmentRequest $request)
    {
        $access_at = isset($request->safe()->access_at) ? $request->safe()->access_at : now();

        $subject = $this->assignment->create(
            $request->safe()->subject_id,
            $request->safe()->title,
            $request->safe()->content,
            $request->safe()->size,
            $access_at,
            $request->safe()->ended_at
            
        );
        return redirect()->route('assignments.index', [
            'subject' => $subject['subject_id']
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $assignment = $this->assignment->find($id);

        return view('pages.admin.assignment.read', [
            'navLink' => [
                ['url' => '/admin/classrooms', 'label' => 'Kelas'],
                ['url' => '/admin/classrooms/'.$assignment['subject']['classroom']['id'], 'label' => $assignment['subject']['classroom']['name']],
                ['url' => '/admin/subjects/'.$assignment['subject']['id'], 'label' => $assignment['subject']['name']],
                ['url' => '/admin/assignments?subject='.$assignment['subject']['id'], 'label' => 'Penugasan'],
                ['url' => '#', 'label' => $assignment['title']],
            ],
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
        $assignment = $this->assignment->singleFind($id);

        return view('pages.admin.assignment.edit', [
            'navLink' => [
                ['url' => '/admin/classrooms', 'label' => 'Kelas'],
                ['url' => '/admin/classrooms/'.$assignment['subject']['classroom']['id'], 'label' => $assignment['subject']['classroom']['name']],
                ['url' => '/admin/subjects/'.$assignment['subject']['id'], 'label' => $assignment['subject']['name']],
                ['url' => '/admin/assignments?subject='.$assignment['id'], 'label' => 'Penugasan'],
                ['url' => '#', 'label' => 'Edit'],
            ],
            'assignment' => $assignment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssignmentRequest $request, string $id)
    {
        $access_at = isset($request->safe()->access_at) ? $request->safe()->access_at : now();

        $assignment = $this->assignment->update(
            $id,
            $request->safe()->title,
            $request->safe()->content,
            $request->safe()->size,
            $access_at,
            $request->safe()->ended_at,
        );

        return redirect()->route('assignments.index', [
            'subject' => $assignment['subject_id']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bool = $this->assignment->delete($id);

        return redirect()->back();
    }
}
