<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUpdateBillboardRequest extends FormRequest
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
            'judul'     => ['required', 'string', 'min:5', 'max:100'],
            'area'      => ['required', 'string', 'min:5', 'max:50'],
            'lokasi'    => ['required', 'string', 'min:5', 'max:255'],
            'jenis'     => ['required', 'string', 'min:5', 'max:100'],

            'lebar'     => ['required', 'numeric', 'min:1', 'max:99'],
            'panjang'   => ['required', 'numeric', 'min:1', 'max:99'],
            'unit'      => ['required', 'integer', 'min:1', 'max:99'],

            'latitude'  => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],

            'aktif' => 'required|string|in:Aktif,Nonaktif',
            'keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }
}
