<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'route_id',
        'location_id',
        'name',
        'email',
        'mobile_number',
        'password',
        'role',
        'serial_number',
        'serial_expires_at',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'route_id' => 'integer',
            'location_id' => 'integer',
        ];
    }

    /**
     * Get the user's name.
     */
    protected function name(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function (?string $value) {
                if (auth()->check() && auth()->id() === $this->id && Session::has('user_display_name')) {
                    return Session::get('user_display_name');
                }
                return $value;
            },
        );
    }

    public static function generateSerialNumber()
    {
        do {
            $serial = 'SAGAKI-'.strtoupper(Str::random(8));
        } while (self::where('serial_number', $serial)->exists());

        return $serial;
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
