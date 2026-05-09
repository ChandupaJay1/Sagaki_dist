<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    protected $fillable = [
        'payment_id',
        'cheque_no',
        'date',
        'bank_name',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
