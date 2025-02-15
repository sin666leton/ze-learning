<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreStudentRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'nis' => 'required|unique:students,nis',
            'password' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'nis' => 'NIS',
            'password' => 'Password'
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
