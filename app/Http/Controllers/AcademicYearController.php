<?php

namespace App\Http\Controllers;

use App\Contracts\AcademicYear;
use App\Http\Requests\StoreAcademicYearRequest;
use App\Http\Requests\UpdateAcademicYearRequest;
use App\Services\AcademicYearService;
use Log;

class AcademicYearController extends Controller
{
    public function __construct(
        protected AcademicYearService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->service->paginate();
        return view('pages.admin.academic_year.index', [
            'academicYear' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.academic_year.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAcademicYearRequest $request)
    {
        $result = $this->service->create($request->safe()->name);
        Log::info("Academic year created.", $result->toArray());

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
        $data = $this->service->find($id);
        return view('pages.admin.academic_year.edit', [
            'academicYear' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcademicYearRequest $request, string $id)
    {
        $this->service->update($id, $request->safe()->name);
        Log::info("Academic year updated.", [
            ...$request->safe()->toArray(),
            'id' => $id
        ]);

        return redirect()->route('academic-years.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->service->delete($id);
        Log::info("Academic year deleted.", [
            'id' => $id
        ]);

        return redirect()->route('academic-years.index');
    }
}
