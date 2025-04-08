<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidNip implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $nip = preg_replace('/[^0-9]/', '', $value);

        // Waliduj długość
        if (strlen($nip) !== 10) {
            $fail("Pole :attribute musi zawierać dokładnie 10 cyfr.");
            return;
        }

        // sprawdzanie sumy kontrolnej NIP, ale dla lepszej walidacji warto odpytać np. api gus
        $weights = [6, 5, 7, 2, 3, 4, 5, 6, 7];
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $weights[$i] * (int)$nip[$i];
        }

        $control = $sum % 11;

        if ($control === 10 || $control !== (int)$nip[9]) {
            $fail("Pole :attribute zawiera niepoprawny numer NIP.");
        }
    }
}
