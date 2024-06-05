<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotZeroPeso implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === '₱0.00') {
            $fail("The {$attribute} must not be ₱0.00.");
        }
    }
}
