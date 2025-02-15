<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FindStudentRequest extends FormRequest
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
            'classroomID' => 'required|integer',
            'nis' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'classroomID' => 'Kelas',
            'nis' => 'NIS'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute tidak valid',
            'integer' => ':attribute tidak valid'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()->all()
        ], 400));
    }
}
