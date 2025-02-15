<?php

namespace App\Rules;

use App\Models\Student;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NISRule implements ValidationRule
{
    public function __construct(protected $data)
    {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->data->nis != $value) {
            if (Student::where('nis', $value)->exists()) {
                $fail("$attribute telah terdaftar, gunakan yang lain");
            }
        }
    }
}
