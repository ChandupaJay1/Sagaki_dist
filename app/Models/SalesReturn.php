<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    protected $fillable = [
        'customer_id',
        'date',
        'reference_no',
        'memo',
        'header_discount_percent',
        'header_discount_amount',
        'total_amount',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SalesReturnItem::class);
    }
}
