<?php

use Illuminate\Support\Facades\Route;
use Modules\Library\Controllers\AuthorController;
use Modules\Library\Controllers\BookController;
use Modules\Library\Controllers\BorrowController;

Route::middleware(['web', 'auth'])->prefix('library')->name('library.')->group(function () {
    Route::resource('authors', AuthorController::class);
    Route::resource('books', BookController::class);
    Route::post('books/{book}/borrow', [BorrowController::class, 'borrow'])->name('books.borrow');
});
