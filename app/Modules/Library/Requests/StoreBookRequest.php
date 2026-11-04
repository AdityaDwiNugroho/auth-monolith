<?php

namespace Modules\Library\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Mendukung input bilingual (Bahasa Indonesia / Inggris)
        $mergeData = [];

        if ($this->has('judul') && !$this->has('title')) {
            $mergeData['title'] = $this->input('judul');
        }
        if ($this->has('tahun_terbit') && !$this->has('published_year')) {
            $mergeData['published_year'] = $this->input('tahun_terbit');
        }
        if ($this->has('harga') && !$this->has('price')) {
            $mergeData['price'] = $this->input('harga');
        }
        if ($this->has('stok') && !$this->has('stock')) {
            $mergeData['stock'] = $this->input('stok');
        }

        if (!empty($mergeData)) {
            $this->merge($mergeData);
        }
    }

    public function rules(): array
    {
        $currentYear = (int) date('Y');

        return [
            'title' => ['required', 'string', 'max:255'],
            'isbn' => [
                'required',
                'string',
                'unique:books,isbn',
                // Regex memvalidasi format ISBN standar (ISBN-10 atau ISBN-13, dengan atau tanpa tanda hubung)
                'regex:/^(?:ISBN(?:-1[03])?:?\ )?(?=[0-9X]{10}$|(?=(?:[0-9]+[-\ ]){3})[- 0-9X]{13}$|97[89][0-9]{10}$|(?=(?:[0-9]+[-\ ]){4})[- 0-9]{17}$)(?:97[89][-\ ]?)?[0-9]{1,5}[-\ ]?[0-9]+[-\ ]?[0-9]+[-\ ]?[0-9X]$/i',
            ],
            'published_year' => ['required', 'integer', 'min:1900', "max:{$currentYear}"],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'author_id' => ['required', 'integer', 'exists:authors,id'],
        ];
    }

    public function messages(): array
    {
        $currentYear = date('Y');

        return [
            'title.required' => 'Judul buku wajib diisi.',
            'title.string' => 'Judul buku harus berupa teks.',
            'title.max' => 'Judul buku maksimal 255 karakter.',
            'isbn.required' => 'Nomor ISBN wajib diisi.',
            'isbn.unique' => 'Nomor ISBN ini sudah terdaftar.',
            'isbn.regex' => 'Format nomor ISBN tidak valid (contoh: 978-602-03-8829-8 atau 9786020388298).',
            'published_year.required' => 'Tahun terbit wajib diisi.',
            'published_year.integer' => 'Tahun terbit harus berupa bilangan bulat.',
            'published_year.min' => 'Tahun terbit minimal tahun 1900.',
            'published_year.max' => "Tahun terbit tidak boleh melebihi tahun sekarang ({$currentYear}).",
            'price.required' => 'Harga buku wajib diisi.',
            'price.numeric' => 'Harga buku harus berupa angka.',
            'price.min' => 'Harga buku tidak boleh negatif.',
            'stock.required' => 'Stok buku wajib diisi.',
            'stock.integer' => 'Stok buku harus berupa bilangan bulat.',
            'stock.min' => 'Stok buku tidak boleh negatif.',
            'author_id.required' => 'Penulis buku wajib dipilih.',
            'author_id.exists' => 'Penulis yang dipilih tidak valid atau tidak terdaftar di sistem.',
        ];
    }
}
