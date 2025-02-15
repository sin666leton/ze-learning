<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nis' => 'required_if:email,null|integer',
            'email' => 'required_if:nis,null|email',
            'password' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'nis' => 'NIS',
            'email' => 'Email',
            'password' => 'Password'
        ];
    }

    public function messages()
    {
        return [
            'integer' => ':attribute tidak valid',
            'email' => ':attribute tidak valid',
            'required' => ':attribute tidak boleh kosong',
            'required_id' => ':attribute tidak boleh kosong'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('error', 'Email atau password salah');
        throw new ValidationException($validator);
    }
}
