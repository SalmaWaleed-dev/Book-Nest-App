<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // Note: there is intentionally NO 'role' rule here. Even if a client
            // sends role=admin in the POST body, RegisterController never reads
            // it — see validated() usage there. Role is hardcoded server-side.
        ];
    }
}
