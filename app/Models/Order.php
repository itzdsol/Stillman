<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_amount',
        'service_fee',
        'tax_amount',
        'tax',
        'sub_total',
        'total',
        'is_single_cart',
        'address_id',
        'is_pickup',
        'pickup_date',
        'pickup_time',
        'payment_status',
    ];
}
