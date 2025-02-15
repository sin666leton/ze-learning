<?php

namespace App\Http\Controllers;

use App\Exceptions\AssignmentNotExists;
use App\Exports\AssignmentScoreExport;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function assignmentScore(string $id)
    {
        $assignment = Assignment::with([        
            'subject' => function ($query) {
                $query->select(['id', 'name', 'classroom_id', 'semester_id'])
                    ->with([
                        'classroom' => function ($classroom) {
                            $classroom->select(['id', 'name', 'academic_year_id'])
                                ->with([
                                    'academicYear' => function ($academic) {
                                        $academic->select(['id', 'name']);
                                    }
                                ]);
                        }
                    ])
                    ->with([
                        'semester' => function ($semester) {
                            $semester->select(['id', 'name']);
                        }
                    ]);
            },
            'answerAssignments' => function ($query) {
                $query->select([
                    'id',
                    'assignment_id',
                    'student_classroom_id',
                    'created_at'
                ])
                ->with([
                    'score' => function ($scores) {
                        $scores->select([
                            'id',
                            'scoreable_id',
                            'scoreable_type',
                            'student_classroom_id',
                            'point',
                        ]);
                    }
                ])
                ->with([
                    'studentClassroom' => function ($pivot) {
                        $pivot->select(['id', 'student_id'])
                            ->with([
                                'student' => function ($student) {
                                    $student->select(['id', 'user_id', 'nis'])
                                        ->with([
                                            'user' => function ($user) {
                                                $user->select(['id', 'name']);
                                            }
                                        ]);
                                }
                            ]);
                    }
                ]);
            }
        ])
        ->select([
            'id',
            'subject_id',
            'title'
        ])
        ->where('id', $id)
        ->firstOr(function () {
            throw new AssignmentNotExists();
        });

        // dd($assignment->answerAssignments->toArray());
        return Excel::download(new AssignmentScoreExport(
            $assignment->subject->classroom->academicYear->name,
            $assignment->subject->classroom->name,
            $assignment->subject->semester->name,
            $assignment->subject->name,
            $assignment->answerAssignments,
        ), 'test.xlsx',
            \Maatwebsite\Excel\Excel::CSV, [
            'delimiter' => '',
            'encoding' => 'UTF-8',
        ]);
    }
}
