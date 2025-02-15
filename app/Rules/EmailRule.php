<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailRule implements ValidationRule
{
    public function __construct(protected $data) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->data->user->email != $value) {
            if (User::where('email', $value)->exists()) {
                $fail("$attribute telah terdaftar, gunakan yang lain");
            }
        }
    }
}
