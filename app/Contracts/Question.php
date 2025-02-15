<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface Question
{
    public function all();

    public function find(int $id);

    public function add(
        int $quizID,
        string $content,
        int $point,
        string $type = 'essay' | 'mcq'
    );

    /**
     * Summary of addChoices
     * @param array<
     *  int, array{
     *      question_id: int,
     *      content: string
     *  }> $choice
     */
    public function addChoices(array $choice);

    /**
     * Summary of updateChoices
     * @param Collection<int, \App\Models\Choice>
     * 
     * @param array<
     *  int, array{
     *      content: string
     *  }> $choice
     */
    public function updateChoices(Collection  $old, array $choice);

    public function addAnswerKey(\App\Models\Question $question, string $content);

    public function updateAnswerKey(int $questionID, string $content);

    public function update(
        int $id,
        string $content,
        int $point,
        string $type = 'mcq' | 'essay'
    );

    public function delete(int $id);

    public function answer(
        int $studentID,
        int $questionID,
        string $content
    );
}
