<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransferItem extends Model
{
    protected $fillable = [
        'inventory_transfer_id',
        'product_id',
        'description',
        'onhand',
        'qty',
        'unit',
    ];

    protected $casts = [
        'inventory_transfer_id' => 'integer',
        'product_id' => 'integer',
        'onhand' => 'double',
        'qty' => 'double',
    ];

    public function inventoryTransfer()
    {
        return $this->belongsTo(InventoryTransfer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
