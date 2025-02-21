<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\StudentNotExists;
use App\Http\Requests\AttachClassroomStudentRequest;
use App\Http\Requests\FindStudentRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\User;
use App\Repositories\V1\StudentRepository;
use App\Services\ClassroomService;
use App\Services\StudentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentManagementController extends Controller
{
    public function __construct(
        protected StudentRepository $student
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = $this->student->paginate(10);

        return view('pages.admin.users.index', [
            'navLink' => [
                ['url' => '#', 'label' => 'Pengguna'],
            ],
            'users' => $student
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ClassroomService $classroom)
    {
        return view('pages.admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $this->student->create(
            $request->safe()->email,
            $request->safe()->name,
            $request->safe()->password,
            $request->safe()->nis
        );

        return redirect()->route('students.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = $this->student->find($id);
        
        return view('pages.admin.users.read', [
            'navLink' => [
                ['url' => '/admin/students', 'label' => 'Pengguna'],
                ['url' => '#', 'label' => $student['nis']]
            ],
            'student' => $student,
            'lastClass' => $student['classrooms'][0]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = $this->student->find($id);

        return view('pages.admin.users.edit', [
            'student' => $student
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, string $id)
    {
        $this->student->update(
            $id,
            $request->safe()->email,
            $request->safe()->name,
            $request->safe()->nis
        );

        return redirect()->route('students.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->student->delete($id);

        return redirect()->route('students.index');
    }

    public function findStudent(FindStudentRequest $request)
    {
        try {
            $data = $this->student->findByNISandClassroom(
                $request->safe()->nis,
                $request->safe()->classroom_id
            );

            $data = [
                'id' => $data['id'],
                'user_id' => $data['user_id'],
                'name' => $data['user']['name'],
                'nis' => $data['nis']
            ];
            return response()->json([
                'data' => $data
            ], 200);
        } catch (StudentNotExists $th) {
            return response()->json([
                'message' => 'Tidak ditemukan'
            ], 404);
        }
    }

    public function addClassroom(AttachClassroomStudentRequest $request)
    {
        $this->student->attachClassroom(
            $request->safe()->student_id,
            $request->safe()->classroom_id
        );

        return redirect()->back()->with(
            'success',
            'Siswa telah ditambahkan ke kelas'
        );
    }

    public function removeClassroom(string $student_id, string $classroom_id)
    {
        $this->student->detachClassroom(
            $student_id,
            $classroom_id
        );

        return redirect()->back()->with(
            'success',
            'Siswa telah dihapus dari kelas'
        );
    }
}
