<?php

namespace Modules\Library\Controllers;

use App\Http\Controllers\Controller;
use Modules\Library\Models\Author;
use Modules\Library\Models\Book;
use Modules\Library\Requests\StoreBookRequest;
use Modules\Library\Requests\UpdateBookRequest;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('author')->paginate(10);
        return view('library.books.index', compact('books'));
    }

    public function create()
    {
        $authors = Author::orderBy('name')->get();
        return view('library.books.create', compact('authors'));
    }

    public function store(StoreBookRequest $request)
    {
        Book::create($request->validated());

        return redirect()
            ->route('library.books.index')
            ->with('success', 'Buku baru berhasil ditambahkan ke perpustakaan.');
    }

    public function edit(Book $book)
    {
        $authors = Author::orderBy('name')->get();
        return view('library.books.edit', compact('book', 'authors'));
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        return redirect()
            ->route('library.books.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $title = $book->title;
        $book->delete();

        return redirect()
            ->route('library.books.index')
            ->with('success', "Buku '{$title}' berhasil dihapus dari perpustakaan.");
    }
}
