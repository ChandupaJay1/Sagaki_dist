<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\GeneratesCode;

class ProductSubCategory extends Model
{
    use HasFactory, GeneratesCode;

    protected $fillable = [
        'item_category_id',
        'name',
        'code',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }
}

