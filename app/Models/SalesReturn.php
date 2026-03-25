<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    protected $fillable = [
        'customer_id',
        'address',
        'delivery_destination',
        'load',
        'return_no',
        'date',
        'create_user',
        'total_amount',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
