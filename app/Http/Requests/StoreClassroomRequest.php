<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreClassroomRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'academic_year_id' => 'required|integer',
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
            'academic_year_id' => 'Bad Request',
            'required' => ':attribute tidak boleh kosong'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('error', $validator->errors()->first());
        throw new ValidationException($validator);
    }
}
