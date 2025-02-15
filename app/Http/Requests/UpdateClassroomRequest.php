<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateClassroomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->teacher()->exists() == 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama kelas'
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
        session()->flash('error', $validator->errors()->first());
        throw new ValidationException($validator);
    }
}
