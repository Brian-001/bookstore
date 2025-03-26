<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
    protected $fillable = [
        'invoice_number',
        'customer_care',
        'total',
        'sale_date',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
