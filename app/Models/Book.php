<?php

namespace App\Models;

use App\Models\Category;
use App\Models\SaleItem;
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

    public function category(){
       return $this->belongsTo(Category::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
