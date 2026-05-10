<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryIssue extends Model
{
    protected $fillable = [
        'issue_no',
        'location_id',
        'account_id',
        'date',
        'memo',
        'status',
        'created_by',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(InventoryIssueItem::class);
    }
}
