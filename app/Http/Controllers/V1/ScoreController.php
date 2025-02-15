<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ScoreNotExists;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateScoreRequest;
use App\Models\Score;
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

        if ($type == 'assignment') {
            $score = Score::with([
                'scoreable' => function ($query) {
                    $query->when($query->getBindings() instanceof \App\Models\AnswerAssignment);
                    $query->with([
                        'assignment' => function ($assignment) {
                            $assignment->select(['id', 'subject_id', 'title'])
                                ->with([
                                    'subject' => function ($subject) {
                                        $subject->select(['id', 'name']);
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
            ])
            ->select(['id', 'scoreable_id', 'scoreable_type', 'point', 'published'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new ScoreNotExists();
            });

            // dd($score->toArray());

        } elseif ($type == 'quiz') {
            $score = Score::with([
                'scoreable' => function ($query) {
                    $query->when($query->getBindings() instanceof \App\Models\Quiz);
                    $query->select(['id', 'subject_id', 'title']);
                    $query->with([
                        'subject' => function ($subject) {
                            $subject->select(['id', 'name']);
                        }
                    ]);
                },
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
            ])
            ->select(['id', 'student_classroom_id', 'scoreable_id', 'scoreable_type', 'point', 'published'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new ScoreNotExists();
            });

        } else {
            return abort(404);
        }

        return view('pages.admin.score.edit', [
            'score' => $score,
        ]);
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
