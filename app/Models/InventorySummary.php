<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventorySummary extends Model
{
    protected $fillable = [
        'product_id',
        'location_id',
        'qty'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
