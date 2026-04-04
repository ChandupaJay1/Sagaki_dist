<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $fillable = [
        'customer_id',
        'rep_id',
        'location',
        'address',
        'delivery_destination',
        'reference_no',
        'order_date',
        'expected_date',
        'terms',
        'due_date',
        'attent',
        'memo',
        'header_discount_percent',
        'header_discount_amount',
        'total_amount',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
    }

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }
}

