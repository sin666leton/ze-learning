<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreQuizRequest extends FormRequest
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
            'subject_id' => 'required|integer',
            'title' => 'required|max:70',
            'content' => 'required',
            'duration' => 'required|integer|min:1',
            "access_at" => "required_if:now,false|date|after_or_equal:now",
            "ended_at" => "required|date|after:now"
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Judul',
            'content' => 'Deskripsi',
            'duration' => 'Durasi pengerjaan',
            'access_at' => 'Tanggal diakses',
            'ended_at' => 'Tanggal berakhir'
        ];
    }

    public function messages(): array
    {
        return [
            'subject_id' => 'Bad Request',
            'required' => ':attribute tidak boleh kosong',
            'integer' => ':attribute tidak valid',
            'date' => ':attribute tidak valid',
            'min' => ':attribute minimal 1 menit',
            'max' => ':attribute tidak lebih dari 70 huruf',
            'after_or_equal' => ':attribute tidak kurang dari sekarang',
            'after' => ':attribute harus lebih besar dari sekarang',
            'required_if' => ":attribute tidak boleh kosong"
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('error', $validator->errors()->first());
        session()->flashInput($this->only(['title', 'duration', 'access_at', 'ended_at']));

        throw new ValidationException($validator);
    }
}
