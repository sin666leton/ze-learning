<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\AcademicYearNotExists;
use App\Exceptions\SemesterNotExists;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSemesterRequest;
use App\Http\Requests\UpdateSemesterRequest;
use App\Models\AcademicYear;
use App\Models\Semesters;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function __construct(
        protected \App\Contracts\Semester $semester
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, \App\Contracts\AcademicYear $academicYear)
    {
        $academicYearID = $request->academic_year;
        $semesters = [];

        $academicYear = $academicYear->getCollection();

        if ($academicYearID != null) {
            $semesters = $this->semester->loadFromCollection(    $academicYear->where('id', $academicYearID));
        }

        return view('pages.admin.semester.index', [
            'navLink' => [
                ['url' => '/admin/semesters', 'label' => 'Semester'],
            ],
            'total' => count($semesters),
            'semesters' => $semesters,
            'academicYear' => $academicYear
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(\App\Contracts\AcademicYear $academicYear)
    {
        $academicYear = $academicYear->get();

        return view('pages.admin.semester.create', [
            'navLink' => [
                ['url' => '/admin/semesters', 'label' => 'Semester'],
                ['url' => '#', 'label' => 'Tambah'],
            ],
            'academicYear' => $academicYear
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSemesterRequest $request)
    {
        $semester = $this->semester->create($request->safe()->academic_year_id, $request->safe()->name);

        return redirect()->route('semesters.index', [
            'academic_year' => $semester['academic_year_id']
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $semester = $this->semester->find($id);

        return view('pages.admin.semester.edit', [
            'navLink' => [
                ['url' => '/admin/semesters', 'label' => 'Semester'],
                ['url' => '#', 'label' => 'Edit'],
            ],
            'semester' => $semester
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSemesterRequest $request, string $id)
    {
        $semester = $this->semester->update($id, $request->safe()->name);
        
        return redirect()->route('semesters.index', [
            'academic_year' => $semester['academic_year_id']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bool = $this->semester->delete($id);

        if (!$bool) {
            return abort(500);
        }

        return redirect()->back();
    }
}
