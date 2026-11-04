<?php

namespace Modules\Library\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Library\Exceptions\BookNotAvailableException;
use Modules\Library\Models\Book;
use Modules\Library\Models\BorrowLog;
use Modules\Library\Requests\BorrowBookRequest;

class BorrowController extends Controller
{
    public function borrow(BorrowBookRequest $request, Book $book)
    {
        $quantity = (int) $request->input('quantity', 1);

        // Fitur Tambahan (Nilai Plus): Validasi stok sebelum peminjaman & logging error
        if ($book->stock <= 0 || $book->stock < $quantity) {
            throw new BookNotAvailableException(
                bookTitle: $book->title,
                availableStock: $book->stock,
                requestedQuantity: $quantity
            );
        }

        // Kurangi stok buku
        $book->decrement('stock', $quantity);

        // Catat peminjaman
        BorrowLog::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'borrower_name' => $request->input('borrower_name'),
            'quantity' => $quantity,
            'borrow_date' => $request->input('borrow_date', now()->toDateString()),
            'status' => 'borrowed',
        ]);

        Log::info("Peminjaman berhasil dicatat: Buku '{$book->title}' sejumlah {$quantity} untuk {$request->input('borrower_name')}.");

        return redirect()
            ->route('library.books.index')
            ->with('success', "Buku '{$book->title}' berhasil dipinjam sebanyak {$quantity} eksemplar.");
    }
}
