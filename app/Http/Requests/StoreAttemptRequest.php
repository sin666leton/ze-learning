<?php

namespace App\Http\Requests;

use App\Models\Quiz;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAttemptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->student()->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'quiz_id' => 'required|exists:quizzes,id',
            'subject_id' => 'required|exists:subjects,id'
        ];
    }

    public function attributes(): array
    {
        return [
            'quiz_id' => 'Kuis',
            'subject_id' => 'Mata pelajaran'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute tidak boleh kosong',
            'exists' => ':attribute tidak valid'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()->first()
        ], 400));
    }

    public function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Unauthorized'
        ], 401));
    }
}
