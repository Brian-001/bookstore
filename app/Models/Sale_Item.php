<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale_Item extends Model
{
    //
    protected $fillable = [
        'sale_id',
        'book_id',
        'quantity',
        'price',
    ];
}
