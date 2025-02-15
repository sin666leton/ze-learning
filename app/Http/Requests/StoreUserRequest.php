<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreUserRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role == 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'classroom_id' => 'required',
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'nis' => 'required|unique:users,nis',
            'password' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'classroom_id' => 'Kelas',
            'name' => 'Nama',
            'email' => 'Email',
            'nis' => 'NIS',
            'password' => 'Password'
        ];
    }

    public function messages(): array
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
