<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrnItem extends Model
{
    protected $fillable = [
        'grn_id',
        'product_id',
        'description',
        'qty',
        'rate',
        'amount',
        'disc_percent',
        'discount',
        'total',
        'location',
        'unit',
    ];

    public function grn()
    {
        return $this->belongsTo(Grn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
