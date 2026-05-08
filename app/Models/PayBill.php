<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
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

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(PayBillItem::class);
    }
}
