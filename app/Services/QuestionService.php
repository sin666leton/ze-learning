<?php
namespace App\Services;

use App\Contracts\Question;

class QuestionService
{
    public function __construct(
        protected Question $repository
    ) {}

    /**
     * Summary of create
     * @param array{
     *  quiz_id: int,
     *  content: string,
     *  type: string,
     *  point: int
     * } $validatedInput
     */
    public function create(array $validatedInput)
    {
        return $this->repository->add(
            $validatedInput['quiz_id'],
            $validatedInput['content'],
            $validatedInput['point'],
            $validatedInput['type']
        );
    }

    /**
     * Summary of update
     * @param int $id
     * @param array{
     *  content: string,
     *  type: string,
     *  point: int
     * } $validatedInput
     * @return void
     */
    public function update(int $id, array $validatedInput)
    {
        $this->repository->update(
            $id,
            $validatedInput['content'],
            $validatedInput['point'],
            $validatedInput['type']
        );
    }
    
    public function createChoices(array $validatedInput)
    {
        $this->repository->addChoices($validatedInput);
    }

    public function createAnswerKey(\App\Models\Question $question, string $content)
    {
        $this->repository->addAnswerKey($question, $content);
    }

    public function updateChoices(int $questionID, array $validatedInput)
    {
        $old = $this->repository->find($questionID)
            ->choices()->get();

        $this->repository->updateChoices(
            $old,
            $validatedInput
        );
    }

    public function updateAnswerKey(int $questionID, string $content)
    {
        $this->repository->updateAnswerKey($questionID, $content);
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
    }

    public function buildChoices(int $questionID, array $choices)
    {
        return array_map(function ($item) use ($questionID) {
            $item['question_id'] = $questionID;
            return $item;
        }, $choices, array_keys($choices));
    }
}
