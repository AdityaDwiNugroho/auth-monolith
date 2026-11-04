<?php

namespace Modules\Library\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class BookNotAvailableException extends Exception
{
    protected string $bookTitle;
    protected int $availableStock;
    protected int $requestedQuantity;

    public function __construct(string $bookTitle, int $availableStock = 0, int $requestedQuantity = 1)
    {
        $this->bookTitle = $bookTitle;
        $this->availableStock = $availableStock;
        $this->requestedQuantity = $requestedQuantity;

        $message = "Buku '{$bookTitle}' tidak dapat dipinjam karena stok tidak mencukupi. Tersedia: {$availableStock}, diminta: {$requestedQuantity}.";

        // Fitur Tambahan (Nilai Plus): Log error ke storage/logs/laravel.log
        Log::error("[BookNotAvailableException] {$message}", [
            'book_title' => $bookTitle,
            'available_stock' => $availableStock,
            'requested_quantity' => $requestedQuantity,
        ]);

        parent::__construct($message);
    }

    public function getBookTitle(): string
    {
        return $this->bookTitle;
    }

    public function getAvailableStock(): int
    {
        return $this->availableStock;
    }

    public function getRequestedQuantity(): int
    {
        return $this->requestedQuantity;
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'error' => 'book_not_available',
                'message' => $this->getMessage(),
                'details' => [
                    'book_title' => $this->bookTitle,
                    'available_stock' => $this->availableStock,
                    'requested_quantity' => $this->requestedQuantity,
                ],
            ], 422);
        }

        return back()
            ->withErrors(['borrow_error' => $this->getMessage()])
            ->with('error', $this->getMessage());
    }
}
