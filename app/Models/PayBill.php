<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'vendor_id',
        'customer_id',
        'location_id',
        'voucher_no',
        'date',
        'total_amount',
        'payment_method',
        'cheque_no',
        'pd_cheque_date',
        'memo',
        'status',
    ];

    protected $casts = [
        'vendor_id' => 'integer',
        'customer_id' => 'integer',
        'location_id' => 'integer',
        'total_amount' => 'double',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(PayBillItem::class);
    }
}
