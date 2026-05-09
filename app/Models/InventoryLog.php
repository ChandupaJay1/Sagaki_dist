<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    protected $fillable = [
        'product_id',
        'location_id',
        'reference_type',
        'reference_id',
        'change_qty',
        'after_qty',
        'type',
        'description'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
