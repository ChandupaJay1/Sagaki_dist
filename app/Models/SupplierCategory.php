<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\GeneratesCode;

class SupplierCategory extends Model
{
    use HasFactory, GeneratesCode;

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];
}

