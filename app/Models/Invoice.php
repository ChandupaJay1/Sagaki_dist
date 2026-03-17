<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id',
        'address',
        'delivery_destination',
        'load',
        'invoice_no',
        'date',
        'villa_type',
        'meal_plan',
        'no_of_pax',
        'check_in_date',
        'room_type',
        'check_out_date',
        'total_amount',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
