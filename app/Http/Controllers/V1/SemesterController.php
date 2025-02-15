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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $academicYearID = $request->academic_year;
        $semesters = [];

        $academicYear = AcademicYear::select(['id', 'name'])
            ->get();

        if ($academicYearID != null) {
            $semesters = $academicYear->where('id', $academicYearID)
                ->load([
                    'semesters' => function ($query) {
                        $query->select(['id', 'academic_year_id', 'name']);
                    }
                ]);

            $semesters = $semesters->first()->semesters->toArray();
        }

        return view('pages.admin.semester.index', [
            'total' => count($semesters),
            'semesters' => $semesters,
            'academicYear' => $academicYear
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $academicYear = AcademicYear::select(['id', 'name'])->get();

        return view('pages.admin.semester.create', [
            'academicYear' => $academicYear
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSemesterRequest $request)
    {
        AcademicYear::select('id')
            ->where('id', $request->safe()->academic_year_id)
            ->firstOr(function () {
                throw new AcademicYearNotExists();
            })
            ->semesters()
            ->create([
                'name' => $request->safe()->name
            ]);

        return redirect()->route('semesters.index', [
            'academic_year' => $request->safe()->academic_year_id
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
        $semester = Semesters::with([
            'academicYear' => function ($query) {
                $query->select(['id', 'name']);
            }
        ])
        ->select(['id', 'academic_year_id', 'name'])
        ->where('id', $id)
        ->firstOr(function () {
            throw new SemesterNotExists();
        });

        return view('pages.admin.semester.edit', [
            'semester' => $semester
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSemesterRequest $request, string $id)
    {
        $semester = Semesters::select(['id', 'name', 'academic_year_id'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new SemesterNotExists();
            });
        
        $semester->update([
            'name' => $request->safe()->name
        ]);

        return redirect()->route('semesters.index', [
            'academic_year' => $semester->academic_year_id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Semesters::select(['id', 'name'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new SemesterNotExists();
            })
            ->delete();

        return redirect()->back();
    }
}
