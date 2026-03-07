<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\GeneratesCode;

class Location extends Model
{
    use HasFactory, GeneratesCode;

    protected $fillable = [
        'name',
        'code',
        'contact_no',
        'vehicle_no',
        'is_active',
    ];
}

