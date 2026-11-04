<?php

namespace Modules\Library\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'borrower_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'borrow_date' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'borrower_name.required' => 'Nama peminjam wajib diisi.',
            'borrower_name.string' => 'Nama peminjam harus berupa teks.',
            'borrower_name.max' => 'Nama peminjam maksimal 255 karakter.',
            'quantity.required' => 'Jumlah pinjam wajib diisi.',
            'quantity.integer' => 'Jumlah pinjam harus berupa bilangan bulat.',
            'quantity.min' => 'Jumlah pinjam minimal 1 eksemplar.',
            'borrow_date.date' => 'Format tanggal peminjaman tidak valid.',
        ];
    }
}
