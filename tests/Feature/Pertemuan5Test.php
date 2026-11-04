<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Modules\Library\Models\Author;
use Modules\Library\Models\Book;
use Modules\Products\Models\Product;
use Tests\TestCase;

class Pertemuan5Test extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * 1. Test Validasi Form Request Store Produk (Gagal jika field kosong)
     */
    public function test_store_product_validation_fails_on_empty_fields(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('products.store'), []);

        $response->assertSessionHasErrors(['name', 'slug', 'price', 'stock']);
    }

    /**
     * 2. Test Validasi Unique Slug Produk (Pesan error Bahasa Indonesia)
     */
    public function test_store_product_validation_fails_on_duplicate_slug(): void
    {
        Product::create([
            'name' => 'Produk A',
            'slug' => 'slug-unik-123',
            'price' => 50000,
            'stock' => 10,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('products.store'), [
                'name' => 'Produk B',
                'slug' => 'slug-unik-123',
                'price' => 75000,
                'stock' => 5,
            ]);

        $response->assertSessionHasErrors([
            'slug' => 'Slug produk sudah digunakan.',
        ]);
    }

    /**
     * 3. Test Store Produk Berhasil
     */
    public function test_store_product_success(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('products.store'), [
                'name' => 'SSD NVMe 1TB',
                'slug' => 'ssd-nvme-1tb',
                'price' => 1250000,
                'stock' => 20,
                'description' => 'Storage ultra cepat.',
            ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', ['slug' => 'ssd-nvme-1tb']);
    }

    /**
     * 4. Test InsufficientStockException pada Produk (Web Redirect)
     */
    public function test_insufficient_stock_exception_web_redirect(): void
    {
        $product = Product::create([
            'name' => 'Flashdisk 32GB',
            'slug' => 'flashdisk-32gb',
            'price' => 60000,
            'stock' => 3,
        ]);

        // Minta kurangi stok melebihi stok yang ada
        $response = $this->actingAs($this->user)
            ->post(route('products.reduce-stock', $product->id), [
                'quantity' => 10,
            ]);

        $response->assertSessionHasErrors(['stock']);
    }

    /**
     * 5. Test InsufficientStockException pada Produk (JSON 422)
     */
    public function test_insufficient_stock_exception_json_response(): void
    {
        $product = Product::create([
            'name' => 'MicroSD 64GB',
            'slug' => 'microsd-64gb',
            'price' => 90000,
            'stock' => 2,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('products.reduce-stock', $product->id), [
                'quantity' => 5,
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'error' => 'insufficient_stock',
            'details' => [
                'product' => 'MicroSD 64GB',
                'available' => 2,
                'requested' => 5,
            ],
        ]);
    }

    /**
     * 6. Test Validasi Form Request Penulis (Gagal jika email tidak valid/duplikat)
     */
    public function test_author_validation_fails_on_invalid_data(): void
    {
        Author::create([
            'name' => 'Penulis Pertama',
            'email' => 'penulis1@example.com',
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('library.authors.store'), [
                'name' => '',
                'email' => 'penulis1@example.com',
            ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    /**
     * 7. Test AuthorHasBooksException ketika menghapus penulis yang punya buku
     */
    public function test_author_has_books_exception_prevent_deletion(): void
    {
        $author = Author::create([
            'name' => 'Penulis Bertalenta',
            'email' => 'penulis.talenta@example.com',
        ]);

        Book::create([
            'author_id' => $author->id,
            'title' => 'Mastering Laravel',
            'isbn' => '978-602-03-9999-1',
            'published_year' => 2024,
            'price' => 150000,
            'stock' => 10,
        ]);

        // Coba hapus author
        $response = $this->actingAs($this->user)
            ->delete(route('library.authors.destroy', $author->id));

        $response->assertSessionHasErrors(['author_error']);
        $this->assertDatabaseHas('authors', ['id' => $author->id]);
    }

    /**
     * 8. Test Validasi Form Request Buku (ISBN salah format, tahun > sekarang)
     */
    public function test_book_validation_fails_on_invalid_isbn_and_year(): void
    {
        $author = Author::create([
            'name' => 'Penulis Test',
            'email' => 'author.test@example.com',
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('library.books.store'), [
                'title' => 'Buku Masa Depan',
                'author_id' => $author->id,
                'isbn' => 'bukan-isbn-123',
                'published_year' => date('Y') + 5, // Tahun di masa depan
                'price' => -5000,                  // Harga negatif
                'stock' => -1,                     // Stok negatif
            ]);

        $response->assertSessionHasErrors(['isbn', 'published_year', 'price', 'stock']);
    }

    /**
     * 9. Test BookNotAvailableException saat meminjam buku stok 0 (Nilai Plus: cek log)
     */
    public function test_book_not_available_exception_when_stock_zero(): void
    {
        $author = Author::create([
            'name' => 'Penulis Hebat',
            'email' => 'penulis.hebat@example.com',
        ]);

        $book = Book::create([
            'author_id' => $author->id,
            'title' => 'Buku Habis Stok',
            'isbn' => '978-602-03-0000-0',
            'published_year' => 2023,
            'price' => 75000,
            'stock' => 0, // Stok kosong
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('library.books.borrow', $book->id), [
                'borrower_name' => 'Budi Santoso',
                'quantity' => 1,
            ]);

        $response->assertSessionHasErrors(['borrow_error']);

        // Verifikasi Nilai Plus: Cek apakah error tercatat di laravel.log
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            $logContent = File::get($logPath);
            $this->assertStringContainsString('[BookNotAvailableException]', $logContent);
        }
    }

    /**
     * 10. Test Peminjaman Berhasil dan Pengurangan Stok
     */
    public function test_borrow_book_success_reduces_stock(): void
    {
        $author = Author::create([
            'name' => 'Penulis Sukses',
            'email' => 'penulis.sukses@example.com',
        ]);

        $book = Book::create([
            'author_id' => $author->id,
            'title' => 'Belajar Coding',
            'isbn' => '978-602-03-1111-1',
            'published_year' => 2022,
            'price' => 90000,
            'stock' => 5,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('library.books.borrow', $book->id), [
                'borrower_name' => 'Aditya Dwi Nugroho',
                'quantity' => 2,
            ]);

        $response->assertRedirect(route('library.books.index'));
        $response->assertSessionHas('success');

        // Pastikan stok berkurang dari 5 menjadi 3
        $this->assertEquals(3, $book->fresh()->stock);
        $this->assertDatabaseHas('borrow_logs', [
            'book_id' => $book->id,
            'borrower_name' => 'Aditya Dwi Nugroho',
            'quantity' => 2,
        ]);
    }
}
