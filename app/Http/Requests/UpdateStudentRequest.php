<?php

namespace App\Http\Requests;

use App\Models\Student;
use App\Rules\EmailRule;
use App\Rules\NISRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->teacher()->exists();
    }

    public function prepareForValidation()
    {
        $student = Student::findOrFail($this->student);

        $this->merge(['student' => $student]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => ['required', new EmailRule($this->student)],
            'nis' => ['required', new NISRule($this->student)],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'nis' => 'NIS',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute telah terdaftar, gunakan yang lain'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('error', $validator->errors()->first());
        throw new ValidationException($validator);
    }
}
