<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrnReturn extends Model
{
    protected $fillable = [
        'vendor_id', 'address', 'delivery_destination', 'load', 'return_no', 'date',
        'order_by', 'checked_by', 'rep', 'reference_no', 'invoice_date', 'attent',
        'terms', 'due_date', 'dispatch_no', 'total_amount'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
