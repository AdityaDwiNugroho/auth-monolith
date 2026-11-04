<?php

namespace Modules\Library\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrower_name',
        'quantity',
        'borrow_date',
        'status',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'quantity' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
