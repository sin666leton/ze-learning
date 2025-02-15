<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Services\ClassroomService;
use App\Services\SubjectService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct(
        protected SubjectService $subjectService
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
    public function create(ClassroomService $classroom, Request $request)
    {
        $classID = $request->classroom;
        if ($classID == null) return abort(404);

        return view('pages.admin.subject.create', [
            'classroom' => $classroom->find($request->classroom)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        $result = $this->subjectService->create($request->safe()->all());
    
        return redirect()->route('classrooms.show', [
            'classroom' => $result->classroom_id,
            'semester' => $result->semester_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject = $this->subjectService->find($id);
        return view('pages.admin.subject.read', [
            'subject' => $subject
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('pages.admin.subject.edit', [
            'subject' => $this->subjectService->find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, string $id)
    {
        $this->subjectService->update(
            $id,
            $request->safe()->all()
        );

        $data = $this->subjectService->find($id);
        $param = [
            'classroom' => $data->classroom_id,
            'semester' => $data->semester_id
        ];

        return redirect()->route('classrooms.show', $param);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->subjectService->delete(
            $id
        );
        
        return redirect()->back();
    }
}
