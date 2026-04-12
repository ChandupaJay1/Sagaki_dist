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
        'reference_no',
        'date',
        'expected_date',
        'order_by',
        'checked_by',
        'rep',
        'ship_via',
        'create_user',
        'terms',
        'due_date',
        'attent',
        'memo',
        'subtotal',
        'location_id',
        'payment_term_id',
        'header_discount_percent',
        'header_discount_amount',
        'tax_amount',
        'sscl_percent',
        'sscl_amount',
        'vat_percent',
        'vat_amount',
        'total_amount',
        'account_id',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class, 'payment_term_id');
    }

    public function items()
    {
        return $this->hasMany(SalesReturnItem::class);
    }
}
