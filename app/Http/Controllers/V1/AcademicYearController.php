<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAcademicYearRequest;
use App\Http\Requests\UpdateAcademicYearRequest;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function __construct(
        protected \App\Contracts\AcademicYear $academicYear
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $academicYear = $this->academicYear->paginate(10);

        return view('pages.admin.academic_year.index', [
            'navLink' => [
                ['url' => '#', 'label' => 'Tahun ajaran'],
            ],
            'academicYear' => $academicYear
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.academic_year.create', [
            'navLink' => [
                ['url' => '/admin/academic-years', 'label' => 'Tahun ajaran'],
                ['url' => '#', 'label' => 'Tambah'],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAcademicYearRequest $request)
    {
        $this->academicYear->create($request->safe()->name);

        return redirect()->route('academic-years.index');
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
        $data = $this->academicYear->find($id);
        return view('pages.admin.academic_year.edit', [
            'navLink' => [
                ['url' => '/admin/academic-years', 'label' => 'Tahun ajaran'],
                ['url' => '#', 'label' => 'Edit'],
            ],
            'academicYear' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcademicYearRequest $request, string $id)
    {
        $this->academicYear->update($id, $request->safe()->name);

        return redirect()->route('academic-years.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$this->academicYear->delete($id)) return abort(500);

        return redirect()->route('academic-years.index');
    }
}
