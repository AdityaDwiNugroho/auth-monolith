<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrow_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->string('borrower_name');
            $table->integer('quantity')->default(1);
            $table->date('borrow_date');
            $table->string('status')->default('borrowed'); // 'borrowed', 'returned'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrow_logs');
    }
};
