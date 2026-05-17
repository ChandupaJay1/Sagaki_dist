<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\GeneratesCode;

class Account extends Model
{
    use HasFactory, GeneratesCode;

    protected $fillable = [
        'name',
        'code',
        'type',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        // 1. Prevent deletion of system core master framework rows
        static::deleting(function ($account) {
            $protectedAccounts = ['Accounts Payable', 'Accounts Receivable'];
            if (in_array(trim($account->name), $protectedAccounts)) {
                throw new \Exception("System Override Blocked: The core master account '" . $account->name . "' is system infrastructure and cannot be deleted.");
            }
        });

        // 2. Prevent modification, renaming or editing of system critical strings
        static::updating(function ($account) {
            $protectedOriginals = ['Accounts Payable', 'Accounts Receivable'];
            if (in_array(trim($account->getOriginal('name')), $protectedOriginals) && $account->isDirty('name')) {
                throw new \Exception("System Override Blocked: Core framework structural account names cannot be modified or renamed.");
            }
        });
    }
}
