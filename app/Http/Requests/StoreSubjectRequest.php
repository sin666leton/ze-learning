<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreSubjectRequest extends FormRequest
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
            'classroom_id' => 'required|integer',
            'semester_id' => 'required|integer',
            'name' => 'required',
            'kkm' => 'integer|min:1|max:100|nullable'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'kkm' => 'KKM'
        ];
    }

    public function messages(): array
    {
        return [
            'classroom_id' => 'Bad Request',
            'semester_id' => 'Bad Request',
            'required' => ':attribute tidak boleh kosong',
            'integer' => ':attribute tidak valid',
            'min' => ':attribute tidak kurang dari 1',
            'max' => ':attribute tidak lebih dari 100'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('error', $validator->errors()->first());
        throw new ValidationException($validator);
    }
}
