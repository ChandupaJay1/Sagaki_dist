<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\GeneratesCode;

class PaymentTerm extends Model
{
    use HasFactory, GeneratesCode;

    protected $table = 'terms';

    protected $fillable = [
        'days',
        'code',
        'is_active',
    ];
}

