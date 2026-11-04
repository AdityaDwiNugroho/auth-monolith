<?php

namespace Modules\Library\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:authors,email'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama penulis wajib diisi.',
            'name.string' => 'Nama penulis harus berupa teks.',
            'name.max' => 'Nama penulis maksimal 255 karakter.',
            'email.required' => 'Email penulis wajib diisi.',
            'email.email' => 'Format email penulis tidak valid.',
            'email.max' => 'Email penulis maksimal 255 karakter.',
            'email.unique' => 'Email penulis ini sudah terdaftar di sistem.',
            'bio.string' => 'Bio penulis harus berupa teks.',
            'bio.max' => 'Bio penulis maksimal 1000 karakter.',
        ];
    }
}
