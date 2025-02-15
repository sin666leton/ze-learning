<?php

namespace App\Http\Requests;

use App\Models\Attempt;
use App\Rules\AnswerQuestionRule;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAnswerQuestionRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->student()->exists()) {
            $attempt = Attempt::where('student_id', $this->user()->student->id);
            if ($attempt->exists()) {
                $this->merge(['quiz_id' => $attempt->first()->quiz_id]);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question_id' => ['required', new AnswerQuestionRule($this->quiz_id)],
            'content' => 'required'
        ];
    }

    public function attributes(): array
    {
        return [
            'question_id' => 'Soal',
            'content' => 'Jawaban'
        ];
    }

    public function messages(): array
    {
        return [
            'question_id' => ':attribute tidak valid',
            'required' => ':attribute tidak boleh kosong'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()->first()
        ], 400));
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException(
            'Unauthorization',
            401
        );
    }
}
