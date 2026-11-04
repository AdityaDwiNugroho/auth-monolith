<?php

namespace Modules\Library\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class AuthorHasBooksException extends Exception
{
    protected string $authorName;
    protected int $bookCount;

    public function __construct(string $authorName, int $bookCount)
    {
        $this->authorName = $authorName;
        $this->bookCount = $bookCount;

        $message = "Penulis '{$authorName}' tidak dapat dihapus karena masih memiliki {$bookCount} buku yang terdaftar di perpustakaan.";

        // Fitur Tambahan (Nilai Plus): Log error ke storage/logs/laravel.log
        Log::error("[AuthorHasBooksException] {$message}", [
            'author_name' => $authorName,
            'book_count' => $bookCount,
        ]);

        parent::__construct($message);
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getBookCount(): int
    {
        return $this->bookCount;
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'error' => 'author_has_books',
                'message' => $this->getMessage(),
                'details' => [
                    'author_name' => $this->authorName,
                    'book_count' => $this->bookCount,
                ],
            ], 422);
        }

        return back()
            ->withErrors(['author_error' => $this->getMessage()])
            ->with('error', $this->getMessage());
    }
}
