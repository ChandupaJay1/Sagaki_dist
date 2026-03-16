<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $fillable = [
        'account_id', 'site', 'adjustment_amount', 'adjustment_no', 'memo', 'date'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
