<?php

namespace App\Http\Controllers;

use App\Contracts\Semester;
use App\Http\Requests\StoreSemesterRequest;
use App\Http\Requests\UpdateSemesterRequest;
use App\Services\AcademicYearService;
use App\Services\SemesterService;
use Illuminate\Http\Request;
use Log;

class SemesterController extends Controller
{
    public function __construct(
        protected SemesterService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AcademicYearService $academicYear)
    {
        $data = match ($request->academic_year == null) {
            true => $this->service->all(),
            false => $academicYear->find($request->academic_year)->semesters()->get(),
        };

        return view('pages.admin.semester.index', [
            'total' => count($data),
            'semesters' => $data,
            'academicYear' => $academicYear->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(AcademicYearService $academicYear)
    {
        return view('pages.admin.semester.create', [
            'academicYear' => $academicYear->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSemesterRequest $request)
    {
        $result = $this->service->create(
            $request->safe()->academic_year_id,
            $request->safe()->only(['name', 'start', 'end'])
        );

        Log::info("Semester created.", [
            $result->toArray(),
            'created_by' => $request->user()->id
        ]);

        return redirect()->route('semesters.index');
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
        return view('pages.admin.semester.edit', [
            'semester' => $this->service->find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSemesterRequest $request, string $id)
    {
        $this->service->update(
            $id,
            $request->safe()->only([
                'name', 'start', 'end'
            ])
        );

        Log::info("Semester updated.", [
            ...$request->safe()->toArray(),
            'updated_by' => $request->user()->id
        ]);

        return redirect()->route('semesters.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->service->delete($id);

        Log::info("Semester deleted.", [
            'id' => $id,
            'deleted_by' => auth()->user()->id
        ]);

        return redirect()->route('semesters.index');
    }
}
