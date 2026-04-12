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
        'location_id',
        'payment_term_id',
        'terms',
        'due_date',
        'attent',
        'memo',
        'subtotal',
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

    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class, 'payment_term_id');
    }

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }
}

