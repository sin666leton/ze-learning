<?php
namespace App\Repositories\V1;

use App\Exceptions\AssignmentNotExists;
use App\Exceptions\SubjectNotExists;
use App\Models\Assignment;
use App\Models\Subject;

class AssignmentRepository implements \App\Contracts\Assignment
{
    public function getFromSubject(int $subjectID): array
    {
        $subject = Subject::with([
            'assignments' => function ($query) {
                $query->select([
                    'id',
                    'subject_id',
                    'title',
                    'content',
                    'size',
                    'created_at',
                    'access_at',
                    'ended_at'
                ]);
            },
            'classroom' => function ($query) {
                $query->select(['id', 'name']);
            }
        ])
        ->select(['id', 'classroom_id', 'name'])
        ->where('id', $subjectID)
        ->withCount('assignments')
        ->firstOr(function () {
            throw new SubjectNotExists();
        });

        $subject->assignments->map(function ($key) {
            $key->stat = $key->status;

            return $key;
        });

        return $subject->toArray();
    }

    public function find(int $id): array
    {
        $assignment = $assignment = Assignment::with([
            'subject' => function ($subject) {
                $subject->with([
                    'classroom' => function ($classroom) {
                        $classroom->select(['id', 'name'])
                            ->withCount('students');
                        }
                    ])
                    ->select(['id', 'classroom_id', 'name']);
            },
        ])
        ->select(['id', 'subject_id', 'title', 'content', 'access_at', 'created_at', 'ended_at', 'size'])
        ->where('id', $id)
        ->withCount('answerAssignments')
        ->firstOr(function () {
            throw new AssignmentNotExists();
        });

        $assignment->load([
            'answerAssignments' => function ($answer) {
                $answer->with([
                    'scores' => function ($scores) {
                        $scores->select(['id', 'scoreable_id', 'scoreable_type', 'point', 'published']);
                    }
                ]);
            }
        ]);

        $assignment->subject
            ->classroom
            ->load([
                'students' => function ($students) {
                    $students->with([
                        'user' => function ($user) {
                            $user->select(['id', 'name']);
                        }
                    ]);
                }
            ]);

        $assignment->not_attempted = $assignment->subject->classroom->students_count - $assignment->answer_assignments_count;
        $assignment->created = $assignment->created_at->format('d-m-Y H:i');

        return $assignment->toArray();
    }

    public function create(int $subjectID, string $title, string $content, int $size, string $access_at, string $ended_at): array
    {
        $subject = Subject::select('id')
            ->where('id', $subjectID)
            ->firstOr(function () {
                throw new SubjectNotExists();
            })
            ->assignments()
            ->create([
                'title', $title,
                'content' => $content,
                'access_at' => $access_at,
                'ended_at' => $ended_at,
                'size' => $size
            ]);

        return $subject->only(['id', 'subject_id', 'title']);
    }

    public function update(int $id, string $title, string $content, int $size, string $access_at, string $ended_at): array
    {
        $assignment = Assignment::select(['subject_id', 'id', 'title', 'content', 'size', 'access_at', 'ended_at'])
            ->where('id', $id)
            ->firstOr(function () {
                throw new AssignmentNotExists();
            });

        $assignment->update([
            'title' => $title,
            'content' => $content,
            'size' => $size,
            'access_at' => $access_at,
            'ended_at' => $ended_at
        ]);

        return $assignment->only(['id', 'subject_id', 'title']);
    }

    public function delete(int $id): bool|null
    {
        $assignment = Assignment::select('id')
            ->where('id', $id)
            ->firstOr(function () {
                throw new AssignmentNotExists();
            });
        
        return $assignment->delete();
    }
}
