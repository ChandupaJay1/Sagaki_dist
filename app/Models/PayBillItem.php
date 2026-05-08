<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayBillItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pay_bill_id',
        'grn_id',
        'bill_no',
        'bill_date',
        'due_date',
        'bill_amount',
        'amount_to_pay',
    ];

    public function payBill()
    {
        return $this->belongsTo(PayBill::class);
    }

    public function grn()
    {
        return $this->belongsTo(Grn::class);
    }
}
