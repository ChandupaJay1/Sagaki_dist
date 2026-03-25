<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    protected $fillable = [
        'vendor_id', 'address', 'delivery_destination', 'load', 'grn_no', 'date',
        'order_by', 'checked_by', 'reference_no', 'invoice_date', 'attent',
        'terms', 'due_date', 'manual_no', 'total_amount'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
