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
        protected \App\Contracts\Classroom $classroom
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, \App\Contracts\AcademicYear $academicYear)
    {
        $academicYears = $academicYear->getCollection();
        $lastAcademicYear = $academicYears->last();
        $academicYearID = $request->query('academic_year', $lastAcademicYear->id);

        $classrooms = $this->classroom->loadFromCollection(
            $academicYears->where('id', $academicYearID)
        );

        return view('pages.admin.classroom.index', [
            'navLink' => [
                ['url' => '#', 'label' => 'Kelas'],
            ],
            'total' => count($classrooms),
            'classrooms' => $classrooms,
            'academicYears' => $academicYears,
            'defaultAcademicYear' => $academicYearID
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(\App\Contracts\AcademicYear $academicYear)
    {
        $academicYear = $academicYear->get();

        return view('pages.admin.classroom.create', [
            'navLink' => [
                ['url' => '/admin/classrooms', 'label' => 'Kelas'],
                ['url' => '#', 'label' => 'Tambah']
            ],
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
    public function show(Request $request, string $id)
    {
        // $classroom = $this->service->find($id);
        // $semesters = $semester->fromAcademicYear($classroom->academic_year_id);

        if ($request->category == "student") {
            $classroom = $this->classroom->find($id, 'student');

            return view('pages.admin.classroom.student', [
                'navLink' => [
                    ['url' => '/admin/classrooms', 'label' => 'Kelas'],
                    ['url' => '#', 'label' => $classroom['name']]
                ],
                'classroom' => $classroom
            ]);
        } else {
            $lastSemesterID = $request->query('semester');
            $classroom = $this->classroom->find($id, semesterID: $request->query('semester'));

            return view('pages.admin.classroom.read', [
                'navLink' => [
                    ['url' => '/admin/classrooms', 'label' => 'Kelas'],
                    ['url' => '#', 'label' => $classroom['name']]
                ],
                'classroom' => $classroom,
                'defaultSemesterID' => $lastSemesterID == null ? $classroom['academic_year']['semesters'][array_key_last($classroom['academic_year']['semesters'])]['id'] : $lastSemesterID
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