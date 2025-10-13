<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilInfoRequest extends FormRequest
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
            'perusahaan' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:15',
            'telegram' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
        ];
    }
}
