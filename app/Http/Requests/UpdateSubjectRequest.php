<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateSubjectRequest extends FormRequest
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
            'semester_id' => 'required|exists:semesters,id',
            'name' => 'required',
            'kkm' => 'integer|min:1|max:100|nullable'
        ];
    }

    public function attributes(): array
    {
        return [
            'semester_id' => 'Semester',
            'name' => 'Nama',
            'kkm' => 'KKM'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute tidak boleh kosong',
            'integer' => ':attribute tidak valid',
            'min' => ':attribute tidak kurang dari 1',
            'max' => ':attribute tidak lebih dari 100',
            'exists' => ':attribute tidak valid'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('error', $validator->errors()->first());
        throw new ValidationException($validator);
    }
}
