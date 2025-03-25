<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'price',
        'stock',
        'category_id',
    ];
}
