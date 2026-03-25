<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransfer extends Model
{
    protected $fillable = [
        'site_from', 'site_to', 'transfer_no', 'memo', 'date'
    ];
}
