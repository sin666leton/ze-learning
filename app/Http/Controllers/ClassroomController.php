<?php

namespace App\Http\Controllers;

use App\Contracts\Classroom;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use App\Services\AcademicYearService;
use App\Services\ClassroomService;
use App\Services\SemesterService;
use Illuminate\Http\Request;
use Log;

class ClassroomController extends Controller
{
    public function __construct(
        protected ClassroomService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AcademicYearService $academicYear)
    {        
        if ($request->academic_year != null) $academic = $request->academic_year;
        else $academic = $academicYear->latest()->id;
        
        $data = $this->service->getByAcademicYear($academic);

        return view('pages.admin.classroom.index', [
            'total' => count($data),
            'classrooms' => $data,
            'academicYears' => $academicYear->all(),
            'defaultAcademicYear' => $academic
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(AcademicYearService $academicYear)
    {
        return view('pages.admin.classroom.create', [
            'academicYears' => $academicYear->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassroomRequest $request)
    {
        $result = $this->service->create(
            $request->safe()->academic_year_id,
            $request->safe()->only('name')
        );

        Log::info("Classroom created.", [
            ...$result->toArray(),
            'created_by' => $request->user()->id
        ]);

        return redirect()->route('classrooms.index', [
            'academic_year' => $result->academic_year_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, SemesterService $semester, string $id)
    {
        $classroom = $this->service->find($id);
        $semesters = $semester->fromAcademicYear($classroom->academic_year_id);

        if ($request->category == "student") {
            
            $students = $classroom->students()->get();
            $subjects = $classroom
                ->subjects()
                ->count();

            return view('pages.admin.classroom.student', [
                'classroom' => $classroom,
                'totalSubject' => $subjects,
                'students' => $students,
                'totalStudent' => count($students)
            ]);
        } else {
            $semesterID = $request->semester != null ? $semesterID = $request->semester : $semesterID = $semesters->first->id;
            $students = $classroom->students()->count();
            $subjects = $classroom
                ->subjects()
                ->where('semester_id', $semesterID)
                ->get();
            
            return view('pages.admin.classroom.read', [
                'classroom' => $classroom,
                'totalSubject' => count($subjects),
                'semesters' => $semesters,
                'subjects' => $subjects,
                'totalStudent' => $students
            ]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('pages.admin.classroom.edit', [
            'classroom' => $this->service->find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassroomRequest $request, string $id)
    {
        $this->service->update($id, $request->safe()->only('name'));

        Log::info("Classroom updated.", [
            ...$request->safe()->toArray(),
            'updated_by' => $request->user()->id
        ]);

        return redirect()->route('classrooms.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->service->delete($id);

        Log::info("Classroom deleted.", [
            'id' => $id,
            'deleted_by' => auth()->user()->id
        ]);

        return redirect()->back();
    }
}
