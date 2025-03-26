<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    //
    protected $fillable = [
        'sale_id',
        'book_id',
        'quantity',
        'price',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    //Accessor for subtotal
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }
}
