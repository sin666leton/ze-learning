<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\EmailRule;
use App\Rules\NISRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateUserRequest extends FormRequest
{
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
        $user = User::find($this->student);

        return [
            'classroom_id' => 'required|exists:classrooms,id',
            'name' => 'required',
            'email' => ['required', new EmailRule($user)],
            'nis' => ['required', new NISRule($user)],
            'password' => 'nullable'
        ];
    }

    public function attributes(): array
    {
        return [
            'classroom_id' => 'Kelas',
            'name' => 'Nama',
            'nis' => 'NIS',
            'email' => 'Email'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute telah terdaftar, gunakan yang lain',
            'exists' => ':attribute tidak valid'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('error', $validator->errors()->first());
        throw new ValidationException($validator);
    }
}
