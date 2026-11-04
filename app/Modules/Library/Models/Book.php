<?php

namespace Modules\Library\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'isbn',
        'published_year',
        'price',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'published_year' => 'integer',
        'stock' => 'integer',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function borrowLogs()
    {
        return $this->hasMany(BorrowLog::class, 'book_id');
    }
}
