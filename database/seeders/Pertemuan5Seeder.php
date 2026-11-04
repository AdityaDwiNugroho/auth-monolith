<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Products\Models\Category;
use Modules\Products\Models\Product;
use Modules\Library\Models\Author;
use Modules\Library\Models\Book;

class Pertemuan5Seeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Categories
        $cat1 = Category::firstOrCreate(['slug' => 'alat-tulis'], ['name' => 'Alat Tulis']);
        $cat2 = Category::firstOrCreate(['slug' => 'perlengkapan'], ['name' => 'Perlengkapan']);
        $cat3 = Category::firstOrCreate(['slug' => 'buku-umum'], ['name' => 'Buku Umum']);

        // 2. Seed Simple General Products (Barang Sehari-hari Umum)
        $p1 = Product::firstOrCreate(
            ['slug' => 'buku-tulis-kampus'],
            [
                'name' => 'Buku Tulis Kampus',
                'price' => 5000,
                'stock' => 20,
                'description' => 'Buku tulis bergaris ukuran folio isi 50 lembar.',
            ]
        );
        $p1->categories()->syncWithoutDetaching([$cat1->id]);

        $p2 = Product::firstOrCreate(
            ['slug' => 'pulpen-gel-hitam'],
            [
                'name' => 'Pulpen Gel Hitam',
                'price' => 3500,
                'stock' => 50,
                'description' => 'Pulpen tinta gel warna hitam ketebalan 0.5 mm.',
            ]
        );
        $p2->categories()->syncWithoutDetaching([$cat1->id]);

        $p3 = Product::firstOrCreate(
            ['slug' => 'pensil-2b'],
            [
                'name' => 'Pensil 2B',
                'price' => 2500,
                'stock' => 15,
                'description' => 'Pensil grafit standar untuk menggambar dan ujian.',
            ]
        );
        $p3->categories()->syncWithoutDetaching([$cat1->id]);

        $p4 = Product::firstOrCreate(
            ['slug' => 'penghapus-karet'],
            [
                'name' => 'Penghapus Karet',
                'price' => 1500,
                'stock' => 5,
                'description' => 'Penghapus pensil bersih dan tidak meninggalkan noda.',
            ]
        );
        $p4->categories()->syncWithoutDetaching([$cat1->id]);

        $p5 = Product::firstOrCreate(
            ['slug' => 'penggaris-30cm'],
            [
                'name' => 'Penggaris 30cm',
                'price' => 4000,
                'stock' => 2,
                'description' => 'Penggaris plastik transparan dengan skala sentimeter dan inci.',
            ]
        );
        $p5->categories()->syncWithoutDetaching([$cat2->id]);

        // 3. Seed Authors
        $a1 = Author::firstOrCreate(
            ['email' => 'andrea.hirata@example.com'],
            [
                'name' => 'Andrea Hirata',
                'bio' => 'Penulis novel asal Belitung, pengarang Laskar Pelangi.',
            ]
        );

        $a2 = Author::firstOrCreate(
            ['email' => 'tere.liye@example.com'],
            [
                'name' => 'Tere Liye',
                'bio' => 'Penulis buku fiksi dan sastra populer Indonesia.',
            ]
        );

        $a3 = Author::firstOrCreate(
            ['email' => 'pramoedya@example.com'],
            [
                'name' => 'Pramoedya Ananta Toer',
                'bio' => 'Sastrawan terkemuka Indonesia pengarang Tetralogi Buru.',
            ]
        );

        // 4. Seed Books
        Book::firstOrCreate(
            ['isbn' => '978-979-3062-79-2'],
            [
                'author_id' => $a1->id,
                'title' => 'Laskar Pelangi',
                'published_year' => 2005,
                'price' => 89000,
                'stock' => 8,
            ]
        );

        // Buku stok 0 untuk pengujian BookNotAvailableException
        Book::firstOrCreate(
            ['isbn' => '978-979-3062-92-1'],
            [
                'author_id' => $a1->id,
                'title' => 'Sang Pemimpi',
                'published_year' => 2006,
                'price' => 82000,
                'stock' => 0,
            ]
        );

        Book::firstOrCreate(
            ['isbn' => '978-602-03-3295-6'],
            [
                'author_id' => $a2->id,
                'title' => 'Bumi',
                'published_year' => 2014,
                'price' => 95000,
                'stock' => 15,
            ]
        );
    }
}
