<?php

namespace App\Http\Requests;

use App\Enums\QuestionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->teacher()->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(QuestionType::class)],
            'choices' => 'required_if:type,mcq|array|size:3',
            'choices.*.content' => 'required',
            'answer' => 'required_if:type,mcq',
            'point' => 'required|integer|min:1|max:100',
            'content' => 'required'
        ];
    }

    public function attributes(): array
    {
        return [
            'choices' => 'Pilihan ganda',
            'choices.*' => 'Opsi ke-:index',
            'answer' => 'Kunci jawaban',
            'point' => 'Skor',
            'type' => 'Tipe soal',
            'content' => 'Pertanyaan'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute tidak boleh kosong',
            'integer' => ':attribute tidak valid',
            'min' => ':attribute minimal 1',
            'max' => ':attribute maksimal 100',
            'array' => ':attribute tidak valid',
            'size' => ':attribute tidak lebih dari 3 opsi',
            'enum' => ':attribute tidak valid'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()->all()
        ], 400));
    }
}
