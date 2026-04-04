<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    protected $fillable = [
        'vendor_id',
        'date',
        'reference_no',
        'memo',
        'header_discount_percent',
        'header_discount_amount',
        'total_amount',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(GrnItem::class);
    }
}
