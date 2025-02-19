<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\StudentNotExists;
use App\Http\Requests\AttachClassroomStudentRequest;
use App\Http\Requests\FindStudentRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\User;
use App\Services\ClassroomService;
use App\Services\StudentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentManagementController extends Controller
{
    public function __construct(
        protected StudentService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = User::withWhereHas('student', function ($query) {
            $query->select(['id', 'user_id', 'nis']);
        })
        ->select(['id', 'name', 'email', 'created_at'])
        ->paginate(10);

        return view('pages.admin.users.index', [
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
        $this->service->create($request->safe()->all());

        return redirect()->route('students.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::with([
            'user' => function ($query) {
                $query->select(['id', 'name', 'email', 'created_at', 'profile']);
            },
            'classrooms' => function ($query) {
                $query->select(['name', 'academic_year_id'])
                    ->latest('student_classroom.id')
                    ->with([
                        'academicYear' => function ($academic) {
                            $academic->select(['id', 'name']);
                        }
                    ]);
            }
        ])
        ->where('id', $id)
        ->select(['id', 'user_id', 'nis'])
        ->first();

        // dd($student->classrooms[0]->academicYear->name);

        return view('pages.admin.users.read', [
            'student' => $student,
            'lastClass' => $student->classrooms->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassroomService $classroom, string $id)
    {
        $student = $this->service->find($id);

        return view('pages.admin.users.edit', [
            'student' => $student
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, string $id)
    {
        $this->service->update($id, $request->safe()->all());

        return redirect()->route('students.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->service->delete($id);

        return redirect()->route('students.index');
    }

    public function findStudent(FindStudentRequest $request)
    {
        try {
            $classroomID = $request->safe()->classroom_id;

            $data = Student::with('user')
                ->whereDoesntHave('classrooms', function ($query) use ($classroomID) {
                    $query->where('classroom_id', $classroomID);
                })
                ->where('nis', $request->safe()->nis)
                ->firstOr(function () {
                    throw new StudentNotExists();
                });

            $data = [
                'id' => $data->id,
                'user_id' => $data->user_id,
                'name' => $data->user->name,
                'nis' => $data->nis
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
        $this->service->addClassroom(
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
        $this->service->removeClassroom($student_id, $classroom_id);

        return redirect()->back()->with(
            'success',
            'Siswa telah dihapus dari kelas'
        );
    }
}
