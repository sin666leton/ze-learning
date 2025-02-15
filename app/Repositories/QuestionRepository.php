<?php
namespace App\Repositories;

use App\Models\AnswerKey;
use App\Models\AnswerQuestion;
use App\Models\Choice;
use App\Models\Question;
use App\Models\Quiz;
use DB;
use Illuminate\Database\Eloquent\Collection;

class QuestionRepository implements \App\Contracts\Question
{
    public function all()
    {

    }

    public function find(int $id)
    {
        return Question::findOrFail($id);
    }

    public function add(
        int $quizID,
        string $content,
        int $point,
        string $type = 'essay'|'mcq'
    )
    {
        return Quiz::findOrFail($quizID)
            ->questions()->create([
                'content' => $content,
                'point' => $point,
                'type' => $type
            ]);
    }

    public function update(
        int $id,
        string $content,
        int $point,
        string $type = 'mcq'|'essay'
    )
    {
        Question::findOrFail($id)->update([
            'content' => $content,
            'point' => $point,
            'type' => $type
        ]);
    }

    public function addChoices(array $choice)
    {
        Choice::insert($choice);
    }

    public function updateChoices(Collection $old, array $choice)
    {
        DB::transaction(function () use ($old, $choice) {
            foreach ($old as $index => $record) {
                $record->content = $choice[$index]['content'];
                $record->save();
            }
        });
    }

    public function addAnswerKey(Question $question, string $content)
    {
        $question->answerKey()->create([
            'content' => $content
        ]);
    }

    public function updateAnswerKey(int $questionID, string $content)
    {
        AnswerKey::where('question_id', $questionID)
            ->update([
                'content' => $content
            ]);
    }

    public function delete(int $id)
    {
        Question::findOrFail($id)->delete();
    }

    public function answer(
        int $studentID,
        int $questionID,
        string $content
    )
    {
        $isCorrect = false;
        $question = Question::find($questionID);
        
        if ($question->type == 'mcq') {
            $isCorrect = $question->answerKey->content == $content;
        }

        $question->answerQuestion()->updateOrCreate([
            'student_id' => $studentID,
            'question_id' => $questionID
        ], [
            'student_id' => $studentID,
            'question_id' => $questionID,
            'content' => $content,
            'is_correct' => $isCorrect
        ]);
    }
}
