<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsbnValido implements ValidationRule
{
    /**
     * Confere se o valor segue o formato de um ISBN-10 ou ISBN-13,
     * sem hífens. Ensinado no Encontro 4 (Validação Avançada).
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^(97(8|9))?\d{9}(\d|X)$/', (string) $value)) {
            $fail('O :attribute informado não é um ISBN válido.');
        }
    }
}
