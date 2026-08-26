<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id',
        'rep_id',
        'address',
        'delivery_destination',
        'load',
        'invoice_no',
        'date',
        'location_id',
        'payment_term_id',
        'payment_method',
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
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function payBillItems()
    {
        return $this->hasMany(PayBillItem::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
