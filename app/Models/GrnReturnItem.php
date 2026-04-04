<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrnReturnItem extends Model
{
    protected $fillable = [
        'grn_return_id',
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

    public function grnReturn()
    {
        return $this->belongsTo(GrnReturn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
