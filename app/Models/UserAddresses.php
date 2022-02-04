<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddresses extends Model
{
    use HasFactory;

    protected $fillable = [
        'addressline1',
        'addressline2',
        'nearby',
        'pincode',
        'phone',
        'email',
        'name',
        'user_id',
        'default',
        'dial_code'
    ];
}
