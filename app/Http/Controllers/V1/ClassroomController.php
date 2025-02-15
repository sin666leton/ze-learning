<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\AcademicYearNotExists;
use App\Exceptions\ClassroomNotExists;
use App\Exceptions\SemesterNotExists;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Services\ClassroomService;
use App\Services\SemesterService;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function __construct(
        protected ClassroomService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {        
        $academicYearID = $request->academic_year;
        $classrooms = [];

        $academicYear = AcademicYear::select(['id', 'name'])
            ->get();

        if ($academicYearID != null) {
            $classrooms = $academicYear->where('id', $academicYearID)
                ->load([
                    'classrooms' => function ($query) {
                        $query->select(['id', 'academic_year_id', 'name'])
                            ->withCount('students');
                    }
                ])
                ->first()
                ->classrooms
                ->toArray();
            
            // dd($classrooms);
        } else {
            $classrooms = $academicYear->last()
                ->first()
                ->load([
                    'classrooms' => function ($query) {
                        $query->select(['id', 'academic_year_id', 'name'])
                            ->withCount('students');
                    }
                ])
                ->classrooms
                ->toArray();

        }

        return view('pages.admin.classroom.index', [
            'total' => count($classrooms),
            'classrooms' => $classrooms,
            'academicYears' => $academicYear,
            'defaultAcademicYear' => $academicYear->last()->first()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $academicYear = AcademicYear::select(['id', 'name'])->get();

        return view('pages.admin.classroom.create', [
            'academicYears' => $academicYear
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassroomRequest $request)
    {
        $result = AcademicYear::select('id')
            ->where('id', $request->safe()->academic_year_id)
            ->firstOr(function () {
                throw new AcademicYearNotExists();
            })
            ->classrooms()
            ->create([
                'name' => $request->safe()->name
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

        return redirect()->route('classrooms.index');
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