<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class AddUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|min:3',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users'),
            ],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)
                    ->uncompromised()
            ],
            
            'perusahaan' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:15',
            'telegram' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',

            'aktivasi' => 'required|string|in:Aktif,Nonaktif',
            'role' => 'required|string|in:Admin,Klien'
        ];
    }
}
