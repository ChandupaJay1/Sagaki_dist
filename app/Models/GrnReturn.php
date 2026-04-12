<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrnReturn extends Model
{
    protected $fillable = [
        'vendor_id',
        'address',
        'delivery_destination',
        'load',
        'return_no',
        'reference_no',
        'date',
        'invoice_date',
        'expected_date',
        'terms',
        'due_date',
        'attent',
        'memo',
        'dispatch_no',
        'order_by',
        'checked_by',
        'rep',
        'location_id',
        'payment_term_id',
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

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class, 'payment_term_id');
    }

    public function items()
    {
        return $this->hasMany(GrnReturnItem::class);
    }
}
