<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class BankCardValidationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $sum = 0;
        foreach (range(1,16) as $key => $position) {
            $result = $value[$key] * (1 + $position % 2);
            $result -= ($result > 9) * 9;
            $sum += $result;
        }

        if ($sum % 10 != 0) {
            $fail("The :attribute is not a valid card.");
        }
    }
}
