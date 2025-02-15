<?php

namespace App\Http\Requests;

use App\Models\Assignment;
use App\Rules\FormatRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreAnswerAssignmentRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->student()->exists();
    }

    public function prepareForValidation()
    {
        if ($this->assignment_id == null) return abort(404);

        $assignment = Assignment::findOrFail($this->assignment_id);

        $this->merge([
            'size' => $assignment->size * 1024
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'assignment_id' => 'required|integer',
            'file' => "required|file|mimes:pdf|mimetypes:application/pdf|max:$this->size",
            'namespace' => 'string|nullable'
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => 'File'
        ];
    }

    public function messages(): array
    {
        return [
            'assignment_id' => 'Bad Request',
            'required' => ':attribute tidak boleh kosong',
            'mimes' => ':attribute tidak valid',
            'mimetypes' => ':attribute tidak valid',
            'size' => "Ukuran :attribute tidak lebih dari :size",
            'string' => ':attribute tidak valid'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('error', $validator->errors()->first());
        throw new ValidationException($validator);
    }
}
