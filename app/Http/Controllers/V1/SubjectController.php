<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct(
        protected \App\Contracts\Subject $subject
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, \App\Contracts\Classroom $classroom)
    {
        $classID = $request->classroom;
        if ($classID == null) return abort(404);

        $classroom = $classroom->singleFind($classID);
        
        // dd($classroom);

        return view('pages.admin.subject.create', [
            'classroom' => $classroom
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        $subject = $this->subject->create(
            $request->safe()->semester_id,
            $request->safe()->classroom_id,
            $request->safe()->name,
            $request->safe()->kkm == null ? 70 : $request->safe()->kkm
        );

        return redirect()->route('classrooms.show', [
            'classroom' => $subject['classroom_id'],
            'semester' => $subject['semester_id']
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject = $this->subject->find($id);

        return view('pages.admin.subject.read', [
            'navLink' => [
                    ['url' => '/admin/classrooms', 'label' => 'Kelas'],
                    ['url' => '/admin/classrooms/'.$subject['classroom']['id'], 'label' => $subject['classroom']['name']],
                    ['url' => '#', 'label' => $subject['name']]
                ],
            'subject' => $subject
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subject = $this->subject->findSubjectIncludeSemesterList($id);

        return view('pages.admin.subject.edit', [
            'subject' => $subject
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, string $id)
    {
        $subject = $this->subject->update(
            $request->safe()->semester_id,
            $id,
            $request->safe()->name,
            $request->safe()->kkm == null ? 70 : $request->safe()->kkm
        );

        $param = [
            'classroom' => $subject['classroom_id'],
            'semester' => $subject['semester_id']
        ];

        return redirect()->route('classrooms.show', $param);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$this->subject->delete($id)) {
            return abort(500);
        }
        
        return redirect()->back();
    }
}
