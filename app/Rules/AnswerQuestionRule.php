<?php

namespace App\Rules;

use App\Models\Question;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AnswerQuestionRule implements ValidationRule
{
    public function __construct(protected int $quiz_id) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $quiz_id = $this->quiz_id;
        Question::whereHas('quiz', function ($key) use ($quiz_id) {
            $key->where('id', $quiz_id);
        })->findOr($value, function () use ($fail, $attribute) {
            $fail("$attribute tidak valid");
        });
    }
}
