<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateAssignmentRequest extends FormRequest
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
            'now' => 'boolean',
            'title' => 'required|string|max:70',
            'content' => 'required',
            'size' => 'required|integer|min:1',
            'access_at' => 'required_if:now,false|date|after_or_equal:now',
            'ended_at' => 'required|date|after:now',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Judul',
            'content' => 'Deskripsi',
            'size' => 'Ukuran file',
            'access_at' => 'Tanggal diakses',
            'ended_at' => 'Tanggal berakhir',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute tidak boleh kosong',
            'integer' => ':attribute tidak valid',
            'date' => ':attribute tidak valid',
            'min' => ':attribute minimal 1 MB',
            'max' => ':attribute tidak lebih dari 70 huruf',
            'after_or_equal' => ':attribute tidak kurang dari sekarang',
            'after' => ':attribute harus lebih besar dari sekarang'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('error', $validator->errors()->first());

        throw new ValidationException($validator);
    }
}
