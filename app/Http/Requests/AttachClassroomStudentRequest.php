<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AttachClassroomStudentRequest extends FormRequest
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
            'student_id' => 'required|exists:students,id',
            'classroom_id' => 'required|exists:classrooms,id'
        ];
    }

    public function attributes()
    {
        return [
            'student_id' => 'Siswa/Siswi',
            'classroom_id' => 'Kelas'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute tidak valid',
            'exists' => ':attribute tidak ditemukan'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('error', $validator->errors()->first());
        throw new ValidationException($validator);
    }
}
