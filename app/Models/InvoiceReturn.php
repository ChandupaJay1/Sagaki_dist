<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceReturn extends Model
{
    protected $fillable = [
        'invoice_id',
        'product_id',
        'qty',
        'rate',
        'discount',
        'total',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
