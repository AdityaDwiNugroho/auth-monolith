<?php

namespace Modules\Library\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'bio',
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'author_id');
    }
}
