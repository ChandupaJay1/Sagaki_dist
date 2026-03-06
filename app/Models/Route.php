<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\GeneratesCode;

class Route extends Model
{
    use HasFactory, GeneratesCode;

    protected $fillable = [
        'name',
        'code',
        'area',
        'territory_id',
        'area_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'route_id');
    }

    public function refs()
    {
        return $this->hasMany(User::class, 'route_id')->where('role', 'ref');
    }

    public function territory()
    {
        return $this->belongsTo(Territory::class);
    }

    public function areaRef()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
