<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateFormatRequest extends FormRequest
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
        return [
            'type' => 'required|string|unique:formats,type',
            'mimetype' => 'required|unique:formats,mimetype'
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => 'Tipe file',
            'mimetype' => 'Mimetype'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute sudah ada, silahkan isi dengan :attribute yang lain'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('error', $validator->errors()->first());
        throw new ValidationException($validator);
    }
}
