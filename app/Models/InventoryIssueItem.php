<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryIssueItem extends Model
{
    protected $fillable = [
        'inventory_issue_id',
        'product_id',
        'qty',
    ];

    public function issue()
    {
        return $this->belongsTo(InventoryIssue::class, 'inventory_issue_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
