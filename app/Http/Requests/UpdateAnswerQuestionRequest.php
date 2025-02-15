<?php

namespace App\Http\Requests;

use App\Models\AnswerQuestion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAnswerQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->attempt()->exists() && $this->user()->role == 'student') {
            $answer = AnswerQuestion::where('id', $this->answer_question)->where('user_id', $this->user()->id)->exists();

            return $answer;
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
            'content' => 'required'
        ];
    }

    public function attributes(): array
    {
        return [
            'content' => 'Jawaban'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute tidak boleh kosong'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()->all()
        ], 400));
    }

    public function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Unauthorized'
        ], 401));
    }
}
