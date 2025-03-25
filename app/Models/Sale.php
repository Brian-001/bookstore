<?php

namespace App\Models;

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
}
