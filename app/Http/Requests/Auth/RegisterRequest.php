<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', Rule::enum(UserRole::class)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'confirmed', Password::defaults()],
            'admin_code' => [
                Rule::requiredIf(fn (): bool => $this->input('role') === UserRole::Admin->value),
                'nullable',
                'string',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $expected = (string) config('restaurant.admin_registration_code');

                    if ($expected === '') {
                        $fail('Admin registration is turned off. Ask an existing admin to create your account.');

                        return;
                    }

                    if (! hash_equals($expected, (string) $value)) {
                        $fail('That admin access code is not valid.');
                    }
                },
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'admin_code.required' => 'Enter the admin access code to register as an admin.',
        ];
    }
}
