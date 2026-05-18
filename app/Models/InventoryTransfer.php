<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransfer extends Model
{
    protected $fillable = [
        'site_from', 'site_to', 'transfer_no', 'memo', 'date', 'status', 'rep_agent_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'rep_agent_id' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(InventoryTransferItem::class);
    }

    public function repAgent()
    {
        return $this->belongsTo(User::class, 'rep_agent_id');
    }
}
