<?php

namespace App\Features\Auth\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string|Password|Closure>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'bail',
                'required',
                'string',
                'confirmed',
                Password::min(8),
                function (string $attribute, string $value, Closure $fail): void {
                    if (strlen($value) > 72) {
                        $fail('The password must not exceed 72 bytes.');
                    }

                    if (str_contains($value, "\0")) {
                        $fail('The password must not contain null characters.');
                    }
                },
            ],
        ];
    }
}
