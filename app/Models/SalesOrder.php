<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $fillable = [
        'customer_id',
        'address',
        'delivery_destination',
        'reference_no',
        'order_date',
        'expected_date',
        'memo',
        'total_amount',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

