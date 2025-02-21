<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ScoreNotExists;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateScoreRequest;
use App\Models\Score;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(Request $request, string $id)
    {
        $type = $request->type;

        if ($type == null) return abort(404);
        try {
            $score = Score::when(
                $type == 'quiz',
                function ($query) {
                    $query->with([
                        'studentClassroom' => function ($pivot) {
                            $pivot->with([
                                'student' => function ($student) {
                                    $student->with([
                                        'user' => function ($user) {
                                            $user->select(['id', 'name']);
                                        }
                                    ])
                                    ->select(['id', 'user_id', 'nis']);
                                }
                            ])
                            ->select(['student_id', 'id']);
                        },
                        'scoreable' => function ($quiz) {
                            $quiz->with('subject');
                        }
                    ]);
                }
            )
            ->when(
                $type == 'assignment',
                function ($query) {
                    $query->with([
                        'scoreable' => function ($answerAssignment) use ($query) {
                            $query->select(['id', 'assignment_id', 'student_classroom_id']);
                            $answerAssignment->with([
                                'assignment' => function ($assignment) {
                                    $assignment->with([
                                        'subject' => function ($subject) {
                                            $subject->select(['id', 'name']);
                                        }
                                    ])
                                    ->select(['id', 'subject_id', 'title']);
                                }
                            ]);
                        }
                    ]);
                    $query->with([
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
            )
            ->select(['id', 'student_classroom_id', 'scoreable_id', 'scoreable_type', 'point', 'created_at', 'published'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new ScoreNotExists();
            });
        
            return view('pages.admin.score.edit', [
                'score' => $score->toArray(),
            ]);
        } catch (RelationNotFoundException $th) {
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScoreRequest $request, string $id)
    {
        Score::select(['id', 'point', 'published'])
            ->where('id', $id)
            ->firstOr(function() {
                throw new ScoreNotExists();
            })
            ->updateOrCreate([
                'id' => $id
            ], [
                'point' => $request->safe()->point,
                'published' => $request->safe()->published
            ]);

        return redirect()->to("/admin/$request->type/$request->belongsTo");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
